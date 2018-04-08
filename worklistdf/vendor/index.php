<?php

	//indica o caminho do arquivo no servidor
	$arquivo = 'arquivo_de_importacao.txt';

	//cria um array que receber� os dados importados do arquivo txt
	$arquivoArr = array();
	
	//aqui � enviado para fun��o fopen o endere�o do arquivo e a instru��o 'r' que indica 'somente leitura' e coloca o ponteiro no come�o do arquivo
	$arq = fopen($arquivo, 'r');
	
	//vari�vel armazena o total de linhas importadas
	$total_linhas_importadas = 0;
	
	//a fun��o feof retorna true (verdadeiro) se o ponteiro estiver no fim do arquivo aberto
	//a nega��o do retorno de feof indicada pelo caracter "!" do lado esquerdo da fun��o faz com 
	//que o la�o percorra todas as linhas do arquivo at� fim do arquivo (eof - end of file)
	while(!feof($arq)){
		
		//retorna a linha do ponteiro do arquivo			
		$conteudo = fgets($arq);

		//transforma a linha do ponteiro em uma matriz de string, cada uma como substring de string formada a partir do caracter ';'
		$linha = explode(';', $conteudo);
		
		//array recebe as substring contidas na matriz carregada na vari�vel $linha 
		$arquivoArr[$total_linhas_importadas] = $linha;

		//incremente a vari�vel que armazena o total de linhas importadas
		$total_linhas_importadas++;
	}
?>	
	
	<!-- Codifica��o HTML -->
	<table border="1" style="width:100%;">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Profiss�o</th>
				<th>Estado</th>
			</tr>
		</head>
		
		<tbody>
			<?php foreach($arquivoArr as $linha): ?>
				<tr>
					<?php foreach($linha as $campo): ?>
						<td><?php echo $campo ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
<?php
	//imprime a quantidade de linhas importadas
	echo "<br/> Quantidade de linhas importadas = ".$total_linhas_importadas;
?>