<?php

namespace App\Imports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClienteImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

    // Mapeamento de caracteres mal codificados e suas correções
    $characterMap = [
        'Ã' => 'A',   // Caracteres acentuados em maiúsculas
        '�' => 'A',  // Substituir "�" por "Ã£"
        '�' => 'A',  // Substituir "�" por "Ãµ"
        'ã' => 'a',   // Caracteres acentuados em minúsculas
        'Â' => 'A',
        'â' => 'a',
        'À' => 'A',
        'à' => 'a',
        'Á' => 'A',
        'á' => 'a',
        'É' => 'E',
        'é' => 'e',
        'Ê' => 'E',
        'ê' => 'e',
        'Í' => 'I',
        'í' => 'i',
        'Ó' => 'O',
        'ó' => 'o',
        'Ô' => 'O',
        'ô' => 'o',
        'Õ' => 'O',
        'õ' => 'o',
        'Ú' => 'U',
        'ú' => 'u',
        'Ç' => 'Ç',
        'ç' => 'ç',
    ];

    // Converte os valores do CSV para UTF-8 e corrige caracteres mal codificados
    $row = array_map(function ($value) use ($characterMap) {
        return strtr($value, $characterMap);
    }, $row);

    // Busca por um cliente com o mesmo nome completo
    $cliente = Cliente::where('nome_completo', $row['nome_completo'])->first();

    // Se não encontrou um cliente com o mesmo nome completo, cria um novo
    if (!$cliente) {
        $cliente = new Cliente();
    }

    // Preenche os dados do cliente a partir do CSV
    $cliente->nome_completo = $row['nome_completo'];
    $cliente->celular = $row['celular'];
    $cliente->endereco = $row['endereco'];
    $cliente->cidade = $row['cidade'];
    $cliente->uf = $row['uf'];
    // Adicione mais campos conforme necessário

    // Salva o cliente no banco de dados
    $cliente->save();

    // Retorna o cliente (isso é necessário para a interface ToModel)
    return $cliente;

    }
}
