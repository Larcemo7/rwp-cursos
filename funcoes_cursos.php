<?php
/**
 * Funções auxiliares para gerenciamento de cursos
 */

/**
 * Carrega a lista de cursos disponíveis
 */
function obterCursos() {
    return require 'config_cursos.php';
}

/**
 * Busca um curso específico pelo ID
 */
function buscarCursoPorId($id) {
    $cursos = obterCursos();
    foreach ($cursos as $curso) {
        if ($curso['id'] === $id) {
            return $curso;
        }
    }
    return null;
}

/**
 * Busca um curso específico pelo nome do arquivo
 */
function buscarCursoPorArquivo($arquivo) {
    $cursos = obterCursos();
    foreach ($cursos as $curso) {
        if ($curso['arquivo'] === $arquivo) {
            return $curso;
        }
    }
    return null;
}

/**
 * Carrega o conteúdo HTML de um curso
 */
function carregarConteudoCurso($arquivo) {
    $caminhoArquivo = __DIR__ . '/' . $arquivo;
    
    if (!file_exists($caminhoArquivo)) {
        return [
            'sucesso' => false,
            'erro' => "Arquivo não encontrado: {$arquivo}",
            'conteudo' => null
        ];
    }
    
    $conteudo = file_get_contents($caminhoArquivo);
    
    return [
        'sucesso' => true,
        'erro' => null,
        'conteudo' => $conteudo
    ];
}

/**
 * Extrai apenas o conteúdo do container-curso do HTML
 */
function extrairConteudoCurso($html) {
    // Encontrar a posição do início do container-curso
    $posInicio = stripos($html, '<div');
    if ($posInicio === false) {
        return null;
    }
    
    // Encontrar onde começa o container-curso especificamente
    $posContainer = stripos($html, 'container-curso');
    if ($posContainer === false) {
        return null;
    }
    
    // Voltar para encontrar a tag <div que contém container-curso
    $posTagAbertura = strrpos(substr($html, 0, $posContainer), '<div');
    if ($posTagAbertura === false) {
        $posTagAbertura = $posInicio;
    }
    
    // Encontrar o fechamento do container-curso
    // Os arquivos têm estrutura simples: <div class="container-curso"> ... </div> no final
    // Vamos contar as divs para encontrar o fechamento correto
    $conteudoRestante = substr($html, $posTagAbertura);
    $nivel = 0;
    $pos = 0;
    $ultimaPos = 0;
    
    while ($pos < strlen($conteudoRestante)) {
        $tagAbertura = stripos($conteudoRestante, '<div', $pos);
        $tagFechamento = stripos($conteudoRestante, '</div>', $pos);
        
        if ($tagFechamento === false) {
            break;
        }
        
        if ($tagAbertura !== false && $tagAbertura < $tagFechamento) {
            $nivel++;
            $pos = strpos($conteudoRestante, '>', $tagAbertura) + 1;
        } else {
            $nivel--;
            $ultimaPos = $tagFechamento + 6;
            if ($nivel === 0) {
                // Encontrou o fechamento do container principal
                return substr($conteudoRestante, 0, $ultimaPos);
            }
            $pos = $tagFechamento + 6;
        }
    }
    
    // Se não encontrou fechamento correto, retornar tudo a partir do container-curso
    return $conteudoRestante;
}

/**
 * Extrai os estilos CSS do HTML
 */
function extrairEstilosCurso($html) {
    // Usar regex para extrair estilos (mais simples e confiável)
    preg_match('/<style[^>]*>(.*?)<\/style>/s', $html, $matches);
    
    if (!empty($matches[1])) {
        return '<style>' . $matches[1] . '</style>';
    }
    
    // Fallback com DOMDocument
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    libxml_clear_errors();
    
    $xpath = new DOMXPath($dom);
    $estilos = $xpath->query("//style");
    
    if ($estilos->length > 0) {
        $estilo = $estilos->item(0);
        return $dom->saveHTML($estilo);
    }
    
    return null;
}

/**
 * Valida se um arquivo é seguro para incluir
 */
function validarArquivoCurso($arquivo) {
    // Verificar se o arquivo não contém caminhos perigosos
    if (strpos($arquivo, '..') !== false) {
        return false;
    }
    
    // Verificar se o arquivo existe na lista de cursos permitidos
    $curso = buscarCursoPorArquivo($arquivo);
    return $curso !== null;
}
