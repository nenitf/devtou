<?php

namespace Tests\DataProviders\DevToU;

class AtualizaOuCriaPostsLocalmente {
    public function caminhosFelizes()
    {
        yield 'quatidade igual de posts esperados e encontrados' => [
            [
                [
                    "arquivo de a",
                    "123"
                ],
                [
                    "arquivo de b",
                    "321"
                ]
            ], 
            [
                'response' => [
                [
                    'id' => 123,
                    'title' => 'projetoo',
                    'body_markdown' => 'aaaa'
                ],
                [
                    'id' => 321,
                    'title' => 'linguagemm',
                    'body_markdown' => 'bbbb'
                ]
]
            ],
            [
                ['arquivo de a.md', 'aaaa'],
                ['arquivo de b.md', 'bbbb']
            ],
            [
                'arquivo de a.md',
                'arquivo de b.md',
            ]

        ];

        yield 'quatidade maior de posts esperados do que encontrados' => [
            [
                [
                    "arquivo de a",
                    "123"
                ],
                [
                    "arquivo de b",
                    "321"
                ],
                [
                    "arquivo de c",
                    "1234"
                ]
            ], 
            [
                'response' => [
                [
                    'id' => 123,
                    'title' => 'projetoo',
                    'body_markdown' => 'aaaa'
                ],
                ],
            ],
            [
                ['arquivo de a.md', 'aaaa'],
            ],
            [
                'arquivo de a.md'
            ]
        ];

        yield 'quatidade menor de posts esperados do que encontrados' => [
            [
                [
                    "arquivo de c",
                    "1234"
                ]
            ], 
            [
                'response' => [
                [
                    'id' => 123,
                    'title' => 'projetoo',
                    'body_markdown' => 'aaaa'
                ],
                [
                    'id' => 1234,
                    'title' => 'linguagemm',
                    'body_markdown' => 'cc'
                ]
                ],
            ],
            [
                ['arquivo de c.md', 'cc']
            ],
            [
                'arquivo de c.md',
            ]
        ];
    }
}
