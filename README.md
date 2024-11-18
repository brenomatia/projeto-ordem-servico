<p align="center"> <a href="https://laravel.com" target="_blank"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"> </a> </p> <p align="center"> <a href="https://github.com/laravel/framework/actions"> <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"> </a> </p>
Sobre o Sistema de Ordem de Serviço
Este é um sistema desenvolvido com Laravel, projetado para gerenciar ordens de serviço (OS) de forma eficiente e organizada. Ele permite que empresas gerenciem reparos, manutenções e outros serviços prestados, desde o cadastro do cliente até a finalização do serviço.

Funcionalidades Principais
Cadastro de Clientes
Registro completo de clientes:
Nome, telefone, e-mail e endereço.
Histórico de serviços realizados para cada cliente.
Criação de Ordens de Serviço
Cada OS contém:
Número único de identificação gerado automaticamente.
Dados do cliente vinculado à OS.
Descrição do item (exemplo: "parafusadeira") e problema relatado.
Status da OS (Aberta, Em Andamento, Finalizada, Cancelada).
Data de criação e previsão de entrega.
Observações adicionais e peças utilizadas (se aplicável).
Painel de Controle (Dashboard)
Visão geral das ordens de serviço:
Quantidade de ordens em cada status (Aberta, Em Andamento, Finalizada).
Relatórios de ordens concluídas em períodos específicos.
Valores gerados pelas ordens de serviço.
Impressão de OS
Geração de um recibo ou documento PDF para:
Entrega ao cliente no momento do cadastro do item.
Comprovante de finalização do serviço.
Histórico de Ordens
Registro completo de todas as OS realizadas:
Histórico vinculado a cada cliente.
Detalhamento das peças ou serviços aplicados.
Controle de Usuários e Acessos
Diferentes níveis de acesso:
Administradores: Controle total sobre clientes, ordens e relatórios.
Técnicos: Acesso apenas às ordens de serviço atribuídas a eles.
Operadores: Criação e consulta de ordens.
