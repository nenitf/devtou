<?php

namespace App;

class DevToU {
    private $token;
    private $csv;

    use Curl;
    use Parser;
    use File;

    public function __construct(string $token, string $csv) {
        $this->token = $token;
        $this->csv = $csv;
    }

    public function atualizaOuCriaPostsLocalmente() {
        try {
            $csv = $this->parseCsvFile($this->csv, ';');
        } catch (\Exception $e) {
            die("artigos.csv não encontrado");
        }
        $api = $this->get(
            "https://dev.to/api/articles/me/all", 
            [ 
                'header' =>  ["api-key: {$this->token}"],
                'nossl' => true
            ]
        );
        $artigos = $api['response'];
        $arquivos = array_map(function($artigo) use ($csv){
            $parametrosCSV = array_filter($csv, function($linha) use ($artigo){
                return $artigo['id'] == $linha[1];
            });

            if(!empty($parametrosCSV)){
                // primeiro encontrado pelo filter
                // primeira posição do csv é o nome arquivo
                $arquivo = array_pop($parametrosCSV)[0];

                $arquivo .= ".md";

                $this->createOrUpdateFile(
                    $arquivo, $artigo['body_markdown']
                );
                return $arquivo;
            }
        }, $artigos);

        $arquivos = array_values(
            array_filter($arquivos,'strlen')
        );

        return $arquivos;
    }

    public function criaArquivoCSV() {
        $api = $this->get(
            "https://dev.to/api/articles/me/all", 
            [
                'header' =>  ["api-key: {$this->token}"],
                'nossl' => true
            ]
        );
        $response = $api['response'];
        $relacaoDeArtigos = array_map(function($artigo) {
            $artigoArray = [
                'file' => '',
                'id' => $artigo['id'],
                'title' => $artigo['title']
            ];

            return join(";", $artigoArray);
        }, $response);

        $relacaoDeArtigosString = join("\n", $relacaoDeArtigos);

        $erro = $this->createOrUpdateFile(
            __DIR__ . DIRECTORY_SEPARATOR . 'bot-artigos.csv', $relacaoDeArtigosString
        );
    }

    /*
    public function pesquisaTodosArtigos()
    {
        $perfil = $this->get(
            "https://dev.to/api/articles/me/all", 
            [ 'header' =>  ["api-key: {$this->token}"] ]
        );
        //$perfil = file_get_contents(
        //    "https://dev.to/api/articles/me/all",
        //    false,
        //    stream_context_create([
        //        'http' => [
        //            'header' => "api-key: {$this->token}"
        //        ]
        //    ])
        //);

        return $perfil;
    }
     */
}

trait Curl {
    protected function get($endpoint, $options){
        return $this->curl($endpoint, 'get', $options);
    }

    private function curl($endpoint, $method, ?array $options = null){
        $curl = curl_init($endpoint);

        if(!empty($options['header'])){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['header']);
        }

        if(!empty($options['nossl'])){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        $requestInfo = curl_getinfo($curl);

        curl_close($curl);

        return [
            'response' => json_decode($response, true),
            'info' => $requestInfo
        ];
    }
}

trait Parser {
    protected function parseCsvFile(string $filename, string $delimeter){
        if(!is_readable($filename))
            throw new \Exception('Arquivo não encontrado');

        $csv = array_map(function($line) use ($delimeter){
            $line = str_replace("\n", "", $line);
            return explode($delimeter, $line);
        }, file($filename));

        return $csv;
    }
}

trait File {
    protected function createOrUpdateFile(string $filename, string $content){
        file_put_contents($filename, $content);
    }

    protected function getLinesOfFile($filename){
        if(!is_readable($filename))
            throw new \Exception('Arquivo não existe');

        $fileLines = array_map(function($line){
            return str_replace("\n", "", $line);
        }, file($filename));
        return $fileLines;
    }
}


// THANKS: https://stackoverflow.com/a/10770900/9881278
if (isset($argv[0]) && realpath($argv[0]) == realpath(__FILE__)) {
    $tokenFile = __DIR__ . DIRECTORY_SEPARATOR . 'token';
    $postsFile = __DIR__ . DIRECTORY_SEPARATOR . 'artigos.csv';

    if(!is_readable($tokenFile))
        die('Crie o arquivo token');

    $token = str_replace(
        "\n", 
        "",
        file_get_contents($tokenFile)
    );

    $dev = new DevToU($token, $postsFile);
    $dev->criaArquivoCSV();
    $dev->atualizaOuCriaPostsLocalmente();
}
