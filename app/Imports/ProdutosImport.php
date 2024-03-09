<?php

namespace App\Imports;

use App\Models\Produto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdutosImport implements ToModel, WithHeadingRow
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

    // Busca por um produto com o mesmo nome completo
    $produto = Produto::where('descricao', $row['descricao'])->first();

    // Se não encontrou um produto com o mesmo nome completo, cria um novo
    if (!$produto) {
        $produto = new Produto();
    }

    // Preenche os dados do produto a partir do CSV
    $produto->sku = $row['sku'];
    $produto->descricao = $row['descricao'];
    $produto->unidade = $row['unidade'];
    $produto->ncm = $row['ncm'];
    $produto->cst = $row['cst'];
    $produto->letra = $row['letra'];
    $produto->pis = $row['pis'];
    $produto->confins = $row['confins'];

    // Ajustar o formato do valor de venda, se presente
    if (isset($row['pvenda']) && !empty($row['pvenda'])) {
        $pvenda = $row['pvenda'];

        // Remover todos os pontos e vírgulas
        $pvenda = str_replace(['.', ','], '', $pvenda);

        // Remover somente os dois últimos zeros
        $pvenda = preg_replace('/(?<=\d)00$/', '', $pvenda);

        $produto->pvenda = floatval($pvenda);
    }

    // Adicione mais campos conforme necessário

    // Salva o produto no banco de dados
    $produto->save();

    // Retorna o produto (isso é necessário para a interface ToModel)
    return $produto;

    }
}
