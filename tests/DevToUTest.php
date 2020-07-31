<?php

namespace Tests;

/**
 * @coversDefaultClass \App\DevToU
 * @covers ::__construct
 * @testdox Bot
 */
class DevToUTest extends \Tests\TestCase {
    private $token;
    private $csv;

    protected function setUp():void {
        $this->token = "exemplo-de-token";
        $this->csv = "esse-csv-nao-deve-ser-usado";
    }

    public function newBot(?array $methodsToMock = null) {
        return $this->new(
            'App\DevToU',
            $methodsToMock,
            [$this->token, $this->csv]
        );
    }

    /**
     * @covers ::criaArquivoCSV
     */
    public function testDeveCriarArquivoCsv() {
        $bot = $this->newBot(
            ['createOrUpdateFile', 'get']
        );

        $bot->expects($this->once())
            ->method('get')
            ->willReturn([
                'response' => 
                [
                    [
                        'id' => 123,
                        'title' => 'exemplo'
                    ],
                    [
                        'id' => 321,
                        'title' => 'exemplo2'
                    ]
                ]
            ]
            );

        $reflection = new \ReflectionClass(\App\DevToU::class);
        $realpathFile = $reflection->getFileName();
        $realpathCsv = dirname($realpathFile) . DIRECTORY_SEPARATOR . 'bot-artigos.csv';

        $bot->expects($this->once())
            ->method('createOrUpdateFile')
            ->with($realpathCsv, ";123;exemplo\n;321;exemplo2");

        $retorno = $bot->criaArquivoCSV();
    }

    /**
     * @covers ::atualizaOuCriaPostsLocalmente
     * @dataProvider \Tests\DataProviders\DevToU\AtualizaOuCriaPostsLocalmente::caminhosFelizes()
     */
    public function testDeveAtualizarOuCriarPostsLocalmente($csv, $artigos, $escrita, $arquivos) {
        $bot = $this->newBot(
            ['parseCsvFile', 'get', 'createOrUpdateFile']
        );

        $bot->expects($this->once())
            ->method('parseCsvFile')
            ->willReturn($csv);

        $bot->expects($this->once())
            ->method('get')
            ->willReturn($artigos);

        $totalDeRetornos = count($escrita);
        $bot->expects($this->exactly($totalDeRetornos))
            ->method('createOrUpdateFile')
            ->withConsecutive(...$escrita)
            ->willReturn($this->onConsecutiveCalls(true, true));

        $arquivosAtualizados = $bot->atualizaOuCriaPostsLocalmente();
        $this->assertEquals($arquivosAtualizados, $arquivos);
    }
}
