<?php 

	//classe dashboard 
	class Dashboard {

		//variaveis
		public $data_inicio;
		public $data_fim;
		public $numeroVendas;
		public $totalVendas;
		public $clientesAtivos;
		public $clientesInativos;
		public $totalReclamacoes;
		public $totalElogios;
		public $totalSugestoes;
		public $totalDespesas;

		//metodo magico get
		public function __get($attr) {
			return $this->$attr;
		}

		//metodo magico set
		public function __set($attr, $valor) {
			$this->$attr = $valor;
			return $this;
		}

	}

	//classe de conexao com bd
	class Conexao {
		private $host = 'localhost';
		private $dbname = 'dashboard';
		private $user = 'root';
		private $pass = '';

		public function conectar() {
			try {

				$conexao = new PDO(
					"mysql:host=$this->host;dbname=$this->dbname",
					"$this->user",
					"$this->pass"
				);

				//instancia seta o grupo utf8
				$conexao->exec('set charset set utf8');

				return $conexao;

			} catch(PDOException $e) {
				echo '<p>'. $e->getMessege() . '</p>';
			}
		}
	}

	//classe (model)
	class Bd {
		private $conexao;
		private $dashboard;

		public function __construct(Conexao $conexao, Dashboard $dashboard) {
			$this->conexao = $conexao->conectar();
			$this->dashboard = $dashboard;
		}

		public function getNumeroVendas() {
			$query = '
				select 
					count(*) as numero_venda
				from
					tb_vendas 
				where 
					data_venda between :data_inicio and :data_fim';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
			$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->numero_venda;
		}

		public function getTotalVendas() {
			$query = '
				select 
					sum(total) as total_venda
				from
					tb_vendas 
				where 
					data_venda between :data_inicio and :data_fim';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
			$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->total_venda;
		}

		public function getClientesAtivos() {
			$query = '
				select 
					count(*) as total_clientes
				from
					tb_clientes
				where 
					cliente_ativo = :cliente_ativo';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':cliente_ativo', 1);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes;
		}

		public function getClientesInativos() {
			$query = '
				select 
					count(*) as total_clientes
				from
					tb_clientes
				where 
					cliente_ativo = :cliente_ativo';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':cliente_ativo', 0);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes;
		}

		public function getTotalReclamacao() {
			$query = '
				select 
					count(*) as contato
				from
					tb_contatos
				where 
					tipo_contato = :tipo_contato';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':tipo_contato', 1);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->contato;
		}

		public function getTotalElogios() {
			$query = '
				select 
					count(*) as contato
				from
					tb_contatos
				where 
					tipo_contato = :tipo_contato';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':tipo_contato', 2);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->contato;
		}

		public function getTotalSugestoes() {
			$query = '
				select 
					count(*) as contato
				from
					tb_contatos
				where 
					tipo_contato = :tipo_contato';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':tipo_contato', 3);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->contato;
		}

		public function getTotalDespesas() {
			$query = '
				select 
					sum(total) as total_despesas
				from
					tb_despesas
				where 
					data_despesa between :data_inicio and :data_fim';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
			$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
		}

	}

	//instancia da classe Dashboard
	$dashboard = new Dashboard();
	//instancia de Conexao
	$conexao = new Conexao();	
	//instancia de Bd
	$bd = new Bd($conexao, $dashboard);

	$competencia = explode('-', $_GET['competencia']);
	$ano = $competencia[0];
	$mes = $competencia[1];

	$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

	$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
	$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

	$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
	$dashboard->__set('totalVendas', $bd->getTotalVendas());
	$dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
	$dashboard->__set('clientesInativos', $bd->getClientesInativos());
	$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacao());
	$dashboard->__set('totalElogios', $bd->getTotalElogios());
	$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());
	$dashboard->__set('totalDespesas', $bd->getTotalDespesas());

	//print_r($dashboard);
	//print_r($competencia);

	//echo json_encode($dashboard);

?>