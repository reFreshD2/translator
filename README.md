# About

Реализация транслятора из Pascal в C++ с использованием:

- [X] Docker
- [X] Laravel
- [X] Codeception
- [X] Port/Adapter

## Добавление грамматики

```curl
curl -L 'localhost/api/admin/language' -H 'Content-Type: application/json' -d '{
    "language": "Pascal",
    "rules": [
        {
            "tokenType": "separator",
            "type": "regex",
            "rule": "/[;,.]|:(?!=)/"
        },
        {
            "tokenType": "plus",
            "type": "regex",
            "rule": "/[+\-](?!=)/"
        },
        {
            "tokenType": "multiply",
            "type": "regex",
            "rule": "/[*\\/](?!=)/"
        },
        {
            "tokenType": "assigment",
            "type": "regex",
            "rule": "/[+\\-*\\/:]=/"
        },
        {
            "tokenType": "bracket",
            "type": "regex",
            "rule": "/[()]/"
        },
        {
            "tokenType": "string",
            "type": "regex",
            "rule": "/\\'\''.{2,}\\'\''/"
        },
        {
            "tokenType": "char",
            "type": "regex",
            "rule": "/\\'\''.\\'\''/"
        },
        {
            "tokenType": "compare",
            "type": "regex",
            "rule": "/<>|[<>]=?|(?<![:\\-+*\\/])=/"
        },
        {
            "tokenType": "int",
            "type": "regex",
            "rule": "/(?<!\\.)\\d+(?!\\.)/"
        },
        {
            "tokenType": "real",
            "type": "regex",
            "rule": "/\\d+\\.?\\d*/"
        },
        {
            "tokenType": "id",
            "type": "regex",
            "rule": "/[a-zA-Zа-яА-Я_]+/"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "begin"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "end"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "for"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "to"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "var"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "downto"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "do"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "while"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "repeat"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "until"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "if"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "then"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "else"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "case"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "break"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "real"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "char"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "string"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "boolean"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "abs"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "sqr"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "sqrt"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "exp"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "write"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "writeln"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "readln"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "true"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "false"
        },
        {
            "tokenType": "keyword",
            "type": "string",
            "rule": "integer"
        }
    ]
}'
```

## Получение токенов

```curl
curl -L 'localhost/api/lexical' -H 'Content-Type: application/json' -d '{
    "language": "Pascal",
    "input": "var a,b:integer;\nbegin\na:=b;\nend."
}'
```

Переводит код:
```pascal
var a,b:integer;
begin
a:=b;
end.
```
В набор токенов:
```json
[
        {
            "type": "keyword",
            "value": "var",
            "position": {
                "positionLine": 1,
                "positionColumn": 0
            }
        },
        {
            "type": "id",
            "value": "a",
            "position": {
                "positionLine": 1,
                "positionColumn": 4
            }
        },
        {
            "type": "separator",
            "value": ",",
            "position": {
                "positionLine": 1,
                "positionColumn": 6
            }
        },
        {
            "type": "id",
            "value": "b",
            "position": {
                "positionLine": 1,
                "positionColumn": 8
            }
        },
        {
            "type": "separator",
            "value": ":",
            "position": {
                "positionLine": 1,
                "positionColumn": 10
            }
        },
        {
            "type": "keyword",
            "value": "integer",
            "position": {
                "positionLine": 1,
                "positionColumn": 12
            }
        },
        {
            "type": "separator",
            "value": ";",
            "position": {
                "positionLine": 1,
                "positionColumn": 20
            }
        },
        {
            "type": "keyword",
            "value": "begin",
            "position": {
                "positionLine": 2,
                "positionColumn": 0
            }
        },
        {
            "type": "id",
            "value": "a",
            "position": {
                "positionLine": 3,
                "positionColumn": 0
            }
        },
        {
            "type": "assigment",
            "value": ":=",
            "position": {
                "positionLine": 3,
                "positionColumn": 2
            }
        },
        {
            "type": "id",
            "value": "b",
            "position": {
                "positionLine": 3,
                "positionColumn": 5
            }
        },
        {
            "type": "separator",
            "value": ";",
            "position": {
                "positionLine": 3,
                "positionColumn": 7
            }
        },
        {
            "type": "keyword",
            "value": "end",
            "position": {
                "positionLine": 4,
                "positionColumn": 0
            }
        },
        {
            "type": "separator",
            "value": ".",
            "position": {
                "positionLine": 4,
                "positionColumn": 4
            }
        }
    ]
```
