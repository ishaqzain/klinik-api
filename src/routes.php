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
    });
    // POLI
    $app->group('/poli', function () use ($app) {
        $app->get("/", function (Request $request, Response $response) {
            $sql = "SELECT * FROM poli";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });

        $app->get("/{id}", function (Request $request, Response $response, $args) {
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

        $app->get("/search/", function (Request $request, Response $response) {
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

        $app->post("/", function (Request $request, Response $response) {
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

        $app->put("/{id}", function (Request $request, Response $response, $args) {
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

        $app->delete("/{id}", function (Request $request, Response $response, $args) {
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
