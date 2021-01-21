$(document).ready(() => {

	$('#idDocumentacao').on('click', () => {
		//$('#pagina').load('documentacao.html');

		/*$.get('documentacao.html', data => {
			//console.log(data);
			$('#pagina').html(data);
		})*/

		$.post('documentacao.html', data => {
			//console.log(data);
			$('#pagina').html(data);
		})

	})

	$('#idSuporte').on('click', () => {
		//$('#pagina').load('suporte.html');

		/*$.get('suporte.html', data => {
			//console.log(data);
			$('#pagina').html(data);
		})*/

		$.post('suporte.html', data => {
			//console.log(data);
			$('#pagina').html(data);
		})
	})

	//ajax
	$('#idCompetencia').on('change', e => {
		//console.log($(e.target).val());

		let competencia = $(e.target).val();
		//console.log(competencia);

		//parametros (método, url, dados, sucesso, erro)
		$.ajax({
			type: 'GET',
			url: 'app.php',
			data: `competencia=${competencia}`, //x-www-form-urlencoded
			dataType: 'json',
			success: dados => { 
				$('#idNumeroVendas').html(dados.numeroVendas)
				$('#idTotalVendas').html(dados.totalVendas)
				$('#idTotalAtivo').html(dados.clientesAtivos);
				$('#idTotalInativo').html(dados.clientesInativos);
				$('#idTotalReclamacoes').html(dados.totalReclamacoes);
				$('#idTotalElogios').html(dados.totalElogios);
				$('#idTotalSugestoes').html(dados.totalSugestoes);
				$('#idTotalDespesas').html(dados.totalDespesas);
				//console.log(dados.numeroVendas, dados.totalVendas) 
			},
			error: erro => { console.log(erro) }
		})
	})
	
})