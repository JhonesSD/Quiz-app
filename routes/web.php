<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// MIDDLEWARES
/**
 * Funções que acontecem antes de uma execução
 *
 * O que é uma execução ?
 *  Uma execução é a realização de uma função
 *  Pseudo código (Caso de uso)
 *   // O sistema deve  enviar um email para o usuário caso este esteja ativo
 */

$user = [
    "name" => "Erick",
    "email" => "erick.gato@waproject.com.br",
    "active" => true,
];

/**
 * O que eu espero desse caso de uso ?
 *  - enviar um email para o usuário
 * 1. Buscar o usuário no banco de dados
 * 2. Checar se o usuário está ativo
 * 3. Validar o email do usuário
 * 3. Enviar um email para o usuário
 */

// function validaSeOUsuarioEstaAtivo($usuario)
// {}

// function enviarEmailParaOUsuario($usuario)
// {}

// function buscarOUsuarioNoBancoDeDados($usuario)
// {}

// function validarEmailDoUsuario($usuario)
// {}
// /** Main */
// function executaCasoDeUso($passos = [])
// {
//     if(validaSeOUsuarioEstaAtivo($user)) {
//         if (buscarOUsuarioNoBancoDeDados($user)) {
//             if (validarEmailDoUsuario($user)) {
//                 if(condicao($user)) {
//                     enviarEmailParaOUsuario($user);
//                 }
//             }
//         }
//     }
// }

// /// index.php
// /** Middleware && Chain of responsability (Design Pattern) */
// $enviarEmail = executaCasoDeUso( // Context
//     validaSeOUsuarioEstaAtivo(), // handler
//     validarEmailDoUsuario(),  // handler
//     buscarOUsuarioNoBancoDeDados(),  // handler
//     enviarEmailParaOUsuario()  // handler
// );
// $enviarEmail($user);

/**
 * 1. Cada condição é chamada de "Handler"
 * 2. O Aglomerador de handler é chamado de "Contexto"
 *
 *
 * execute <- esse cara (handle1, handl2, handler3)
 */

interface Handler
{
    public function handle(array $params);
};

class ValidaSeOUsuarioEstaAtivoHandler implements Handler
{
    public function __construct(Handler $next = null)
    {
        $this->next = $next;
    }

    public function handle(array $user, Handler $next = null)
    {
        echo ("Checkando se o usuário está ativo...");

        if (!isset($user['ativo']) || $user['ativo'] === false) {
            return;
        }

        if ($this->next) {
            $this->next->handle($user);
        }
    }
};

class EnviaOEmailParaOUsuarioHandler implements Handler
{
    public function __construct(Handler $next = null)
    {
        $this->next = $next;
    }

    public function handle(array $params)
    {
        echo ("Enviando o email para o usuário...");

        if ($this->next) {
            $this->next->handle($params);
        }
    }
}

Route::get('/', function () {
    $firstHandler = new ValidaSeOUsuarioEstaAtivoHandler(
        new EnviaOEmailParaOUsuarioHandler()
    );

    $firstHandler->handle([
        "ativo" => false,
    ]);
});
