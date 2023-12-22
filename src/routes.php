<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

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
    $app->get("/poli/", function (Request $request, Response $response){
        $sql = "SELECT * FROM poli";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $result = $stmt->fetchAll();
        if($mainCount==0) {
            return $this->response->withJson([
                'status' => 'error',
                'message' => 'no result data.'],200);
        }
        return $response
        ->withJson([
            "status" => "success",
            "data" => $result], 200);
        });
    $app->get("/poli/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM poli WHERE id =:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $mainCount=$stmt->rowCount();
        $result = $stmt->fetch();
        if($mainCount==0) {
            return $this->response->withJson([
                'status' => 'error', 'message' =>
                'no result data.'],200);
        }
        return $response->withJson([
            "status" => "success",
            "data" => $result], 200);
    });
    $app->post("/poli/", function (Request $request, Response $response){
        $new_poli = $request->getParsedBody();
        $sql = "INSERT INTO poli (nama_poli) VALUE (:nama_poli)";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":nama_poli" => $new_poli["nama_poli"]
        ];

        if($stmt->execute($data))
          {
             // get data last id
            $id = $this->db->lastInsertId();
            $sql = "SELECT * FROM poli WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
           return $response->withJson(
                ["status" => "success",
                "data" => $result
            ], 200);
          }
        return $response->withJson(
            ["status" => "failed",
            "data" => "error insert poli"], 200);
    });
    $app->put("/poli/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_poli = $request->getParsedBody();
        $sql = "UPDATE poli SET nama_poli=:nama_poli WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nama_poli" => $new_poli["nama_poli"]
        ];
        if($stmt->execute($data)){
            // get response
             $sql = "SELECT * FROM poli WHERE id =:id";
             $stmt_r = $this->db->prepare($sql);
             $stmt_r->execute([":id" => $id]);
             $result = $stmt_r->fetch();
            return $response->withJson(
                [
                "status" => "success",
                "data" => $result], 200);
        }

        return $response->withJson([
            "status" => "failed",
            "data" => "error Update poli"], 200);
    });
    $app->delete("/poli/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        // cari data
        $sql = "SELECT * FROM poli WHERE id =:id";
        $get_stmt = $this->db->prepare($sql);
        $get_stmt->execute([":id" => $id]);
        $result = $get_stmt->fetch();

        //hapus data
        $sql = "DELETE FROM poli WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $data = [":id" => $id];

        if($stmt->execute($data)) {
            return $response->withJson([
                "status" => "success",
                "data" => $result ], 200);

        }

        return $response->withJson([
                "status" => "failed",
                "data" => "error hapus poli"], 200);
    });
    $app->get("/poli/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM poli WHERE nama_poli LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $data = [":nama_poli" => $keyword];
        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    // PASIEN
    $app->get("/pasien/", function (Request $request, Response $response){
        $sql = "SELECT * FROM pasien";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $result = $stmt->fetchAll();
        if($mainCount==0) {
            return $this->response->withJson([
                'status' => 'error',
                'message' => 'no result data.'],200);
        }
        return $response
        ->withJson([
            "status" => "success",
            "data" => $result], 200);
        });
    $app->get("/pasien/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM pasien WHERE id =:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $mainCount=$stmt->rowCount();
        $result = $stmt->fetch();
        if($mainCount==0) {
            return $this->response->withJson([
                'status' => 'error', 'message' =>
                'no result data.'],200);
        }
        return $response->withJson([
            "status" => "success",
            "data" => $result], 200);
    });
    $app->post("/pasien/", function (Request $request, Response $response){
        $new_pasien = $request->getParsedBody();
        $sql = "INSERT INTO pasien (nomor_rm, nama, tanggal_lahir, nomor_telepon, alamat) VALUE (:nomor_rm, :nama, :tanggal_lahir, :nomor_telepon, :alamat)";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":nomor_rm" => $new_pasien["nomor_rm"],
            ":nama" => $new_pasien["nama"],
            ":tanggal_lahir" => $new_pasien["tanggal_lahir"],
            ":nomor_telepon" => $new_pasien["nomor_telepon"],
            ":alamat" => $new_pasien["alamat"],
        ];

        if($stmt->execute($data))
          {
             // get data last id
            $id = $this->db->lastInsertId();
            $sql = "SELECT * FROM pasien WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
           return $response->withJson(
                ["status" => "success",
                "data" => $result
            ], 200);
          }
        return $response->withJson(
            ["status" => "failed",
            "data" => "error insert poli"], 200);
    });
    $app->put("/pasien/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_pasien = $request->getParsedBody();
        $sql = "UPDATE pasien SET nomor_rm=:nomor_rm, nama=:nama, tanggal_lahir=:tanggal_lahir, nomor_telepon=:nomor_telepon, alamat=:alamat WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nomor_rm" => $new_pasien["nomor_rm"],
            ":nama" => $new_pasien["nama"],
            ":tanggal_lahir" => $new_pasien["tanggal_lahir"],
            ":nomor_telepon" => $new_pasien["nomor_telepon"],
            ":alamat" => $new_pasien["alamat"],
        ];
        if($stmt->execute($data)){
            // get response
             $sql = "SELECT * FROM pasien WHERE id =:id";
             $stmt_r = $this->db->prepare($sql);
             $stmt_r->execute([":id" => $id]);
             $result = $stmt_r->fetch();
            return $response->withJson(
                [
                "status" => "success",
                "data" => $result], 200);
        }

        return $response->withJson([
            "status" => "failed",
            "data" => "error Update Pasien"], 200);
    });
    $app->delete("/pasien/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        // cari data
        $sql = "SELECT * FROM pasien WHERE id =:id";
        $get_stmt = $this->db->prepare($sql);
        $get_stmt->execute([":id" => $id]);
        $result = $get_stmt->fetch();

        //hapus data
        $sql = "DELETE FROM pasien WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $data = [":id" => $id];

        if($stmt->execute($data)) {
            return $response->withJson([
                "status" => "success",
                "data" => $result ], 200);

        }

        return $response->withJson([
                "status" => "failed",
                "data" => "error hapus pasien"], 200);
    });
    $app->get("/pasien/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM pasien WHERE nama LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $data = [":nama" => $keyword];
        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });
};
