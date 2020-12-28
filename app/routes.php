<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/buah', function (Request $request, Response $response) {
        $query = $this->db->prepare('SELECT * FROM buah');
        $result = $query->execute();
        if ($result) {
            if ($query->rowCount()) {
                $data = array(
                    'kode' => 1,
                    'keterangan' => 'Sukses',
                    'data' => $query->fetchAll());
            }else{
                $data = array(
                    'kode' => 2,
                    'keterangan' => 'Tidak ada data',
                    'data' => null);
            }
        }else{
            $data = array(
                'kode' => 100,
                'keterangan' => 'Terdapat error',
                'data' => null);
        }
        return $response->withJson($data);
    });

    $app->get('/buah/{id}', function (Request $request, Response $response, array $args) {
        $query = $this->db->prepare('SELECT * FROM buah WHERE id = :id');
        $query->bindParam(':id', $args['id']);
        $result = $query->execute();
        if ($result) {
            if ($query->rowCount()) {
                $data = array(
                    'kode' => 1,
                    'keterangan' => 'Sukses',
                    'data' => $query->fetch());
            }else{
                $data = array(
                    'kode' => 2,
                    'keterangan' => 'Tidak ada data',
                    'data' => null);
            }
        }else{
            $data = array(
                'kode' => 100,
                'keterangan' => 'Terdapat error',
                'data' => null);
        }
        return $response->withJson($data);
    });

    $app->post('/buah', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $nama = filter_var($params['nama'], FILTER_SANITIZE_STRING);
        $query = $this->db->prepare('INSERT INTO buah (nama) VALUES(:nama)');
        $query->bindParam(':nama', $nama);
        $result = $query->execute();
        if ($result) {
            $data = array(
                'kode' => 1,
                'keterangan' => 'Sukses',
                'data' => null);
        }else{
            $data = array(
                'kode' => 100,
                'keterangan' => 'Terdapat error',
                'data' => null);
        }
        return $response->withJson($data);
    });

    $app->put('/buah/{id}', function (Request $request, Response $response, array $args) {
        $params = $request->getParsedBody();
        $nama = filter_var($params['nama'], FILTER_SANITIZE_STRING);
        $query = $this->db->prepare('UPDATE buah SET nama=:nama WHERE id = :id');
        $query->bindParam(':nama', $nama);
        $query->bindParam(':id', $args['id']);
        $result = $query->execute();
        if ($result) {
            $data = array(
                'kode' => 1,
                'keterangan' => 'Sukses',
                'data' => null);
        }else{
            $data = array(
                'kode' => 100,
                'keterangan' => 'Terdapat error',
                'data' => null);
        }
        return $response->withJson($data);
    });

    $app->delete('/buah/{id}', function (Request $request, Response $response, array $args) {
        $query = $this->db->prepare('DELETE FROM buah WHERE id = :id');
        $query->bindParam(':id', $args['id']);
        $result = $query->execute();
        if ($result) {
            $data = array(
                'kode' => 1,
                'keterangan' => 'Sukses',
                'data' => null);
        }else{
            $data = array(
                'kode' => 100,
                'keterangan' => 'Terdapat error',
                'data' => null);
        }
        return $response->withJson($data);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
