<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos os Cursos</title>
    <style>
        /* Estilos unificados */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
            align-items: center;
        }
        .container-principal {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .container-curso {
            width: 100%;
            min-height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 30px 50px;
            box-sizing: border-box;
        }
        .pagina-curso {
            display: block !important;
            max-width: 700px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .pagina-curso.ativa { 
            display: block !important;
        }
        .pagina-curso h4 { 
            margin: 20px; 
            font-size: 22px; 
            text-align: center; 
        }
        .pagina-curso p { 
            font-size: 16px; 
            line-height: 1.6; 
            margin: 20px; 
        }
        .alerta-importante {
            background-color: #fffbe6;
            border: 1px solid #ffe58f;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .print-container {
            border: 5px solid #e0d20f;
            padding: 10px;
            border-radius: 2px;
            background: #d60a0a;
            max-width: 650px;
            margin: 10px auto;
        }
        .print-container img {
            width: 100%;
            max-width: 580px;
            display: block;
            margin: 0 auto 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        /* Estilos específicos para curso_Banners */
        .curso-banners .print-container {
            max-width: 1450px;
            margin: 10px auto;
        }
        .curso-banners .print-container img {
            width: 150%;
            max-width: 800px;
        }
        /* Separador entre cursos */
        .separador-curso {
            width: 80%;
            height: 3px;
            background: linear-gradient(to right, transparent, #ccc, transparent);
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container-principal">
        <h1 style="text-align: center; color: #333; margin: 30px 0;">Todos os Cursos</h1>
        
        <?php
        // Função para incluir apenas o conteúdo HTML, removendo as tags <style>
        function incluirCurso($arquivo, $classeExtra = '') {
            if (file_exists($arquivo)) {
                $conteudo = file_get_contents($arquivo);
                
                // Remove a tag <style> e todo seu conteúdo
                $conteudo = preg_replace('/<style>.*?<\/style>/is', '', $conteudo);
                
                // Adiciona classe extra ao container-curso se especificada
                if (!empty($classeExtra)) {
                    $conteudo = str_replace('<div class="container-curso"', '<div class="container-curso ' . $classeExtra . '"', $conteudo);
                }
                
                // Garante que a página-curso seja exibida
                $conteudo = str_replace('display: none;', 'display: block !important;', $conteudo);
                $conteudo = str_replace('display: none', 'display: block !important', $conteudo);
                
                return $conteudo;
            }
            return '';
        }
        
        // Array com os arquivos de curso
        $cursos = [
            ['arquivo' => 'curso_Banners.html', 'classe' => 'curso-banners'],
            ['arquivo' => 'curso_3.html', 'classe' => ''],
            ['arquivo' => 'curso_4.html', 'classe' => ''],
            ['arquivo' => 'curso_5.html', 'classe' => ''],
            ['arquivo' => 'curso_6.html', 'classe' => ''],
            ['arquivo' => 'curso_7.html', 'classe' => ''],
            ['arquivo' => 'curso_8.html', 'classe' => ''],
            ['arquivo' => 'curso_9.html', 'classe' => ''],
            ['arquivo' => 'curso_10.html', 'classe' => ''],
        ];
        
        // Inclui cada curso
        foreach ($cursos as $index => $curso) {
            echo '<div class="separador-curso"></div>';
            echo incluirCurso($curso['arquivo'], $curso['classe']);
        }
        ?>
        
    </div>
</body>
</html>
