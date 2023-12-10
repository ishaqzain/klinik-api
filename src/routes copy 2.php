<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);

        // POLI
        $app->get("/poli/", function (Request $request, Response $response) {
            $sql = "SELECT * FROM poli";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });

        // route untuk ambil data poli sesuai dengan ID-nya.
        $app->get("/poli/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $sql = "SELECT * FROM poli WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });

        // method untuk pencarain informasi poli berdasarkan nama_poli
        $app->get("/poli/search/", function (
            Request $request,
            Response $response,
            $args
        ) {
            $keyword = $request->getQueryParam("keyword");
            $sql = "SELECT * FROM poli WHERE nama_poli LIKE '%$keyword%'";
            $stmt = $this->db->prepare($sql);
            $data = [":nama_poli" => $keyword];
            $stmt->execute($data);
            $result = $stmt->fetchAll();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });

        // Route untuk menambah data poli baru.
        $app->post("/poli/", function (Request $request, Response $response) {
            $new_poli = $request->getParsedBody();
            $sql = "INSERT INTO poli (nama_poli) VALUE (:nama_poli)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":nama_poli" => $new_poli["nama_poli"],
            ];
            if ($stmt->execute($data)) {
                return $response->withJson(
                    ["status" => "success", "data" => "1"],
                    200
                );
            }
            return $response->withJson(["status" => "failed", "data" => "0"], 200);
        });

        // untuk edit data poli
        $app->put("/poli/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $new_poli = $request->getParsedBody();
            $sql = "UPDATE poli SET nama_poli=:nama_poli WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":id" => $id,
                ":nama_poli" => $new_poli["nama_poli"],
            ];
            if ($stmt->execute($data)) {
                return $response->withJson(
                    ["status" => "success", "data" => "1"],
                    200
                );
            }
            return $response->withJson(["status" => "failed", "data" => "0"], 200);
        });

        // route ini untuk menghapus data poli
        $app->delete("/poli/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $sql = "DELETE FROM poli WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":id" => $id,
            ];
            if ($stmt->execute($data)) {
                return $response->withJson(
                    ["status" => "success", "data" => "1"],
                    200
                );
            }
            return $response->withJson(["status" => "failed", "data" => "0"], 200);
        });
    });
};
