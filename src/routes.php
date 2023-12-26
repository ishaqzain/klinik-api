<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get("/[{name}]", function (
        Request $request,
        Response $response,
        array $args
    ) use ($container) {
        // Sample log message
        $container->get("logger")->info("Slim-Skeleton '/' route");
        // Render index view
        return $container
            ->get("renderer")
            ->render($response, "index.phtml", $args);
    });

    // GROUP POLI
    $app->group("/poli", function (App $app) {
        // GET all poli
        $app->get("/", function (Request $request, Response $response) {
            $sql = "SELECT * FROM poli";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetchAll();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // GET a specific poli by ID
        $app->get("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $sql = "SELECT * FROM poli WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetch();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // POST a new poli
        $app->post("/", function (Request $request, Response $response) {
            $new_poli = $request->getParsedBody();
            $sql = "INSERT INTO poli (nama_poli) VALUE (:nama_poli)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":nama_poli" => $new_poli["nama_poli"],
            ];

            if ($stmt->execute($data)) {
                // get data last id
                $id = $this->db->lastInsertId();
                $sql = "SELECT * FROM poli WHERE id =:id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":id" => $id]);
                $result = $stmt->fetch();
                return $response->withJson(
                    ["status" => "success", "data" => $result],
                    200
                );
            }
            return $response->withJson(
                ["status" => "failed", "data" => "error insert poli"],
                200
            );
        });

        // UPDATE a poli by ID
        $app->put("/{id}", function (
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
                // get response
                $sql = "SELECT * FROM poli WHERE id =:id";
                $stmt_r = $this->db->prepare($sql);
                $stmt_r->execute([":id" => $id]);
                $result = $stmt_r->fetch();
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error Update poli",
                ],
                200
            );
        });

        // DELETE a poli by ID
        $app->delete("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
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

            if ($stmt->execute($data)) {
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error hapus poli",
                ],
                200
            );
        });

        // Search poli by name
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
    });

    // GROUP PASIEN
    $app->group("/pasien", function (App $app) {
        // GET all pasien
        $app->get("/", function (Request $request, Response $response) {
            $sql = "SELECT * FROM pasien";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetchAll();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // GET a specific pasien by ID
        $app->get("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $sql = "SELECT * FROM pasien WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetch();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // POST a new pasien
        $app->post("/", function (Request $request, Response $response) {
            $new_pasien = $request->getParsedBody();
            $sql =
                "INSERT INTO pasien (nomor_rm, nama, tanggal_lahir, nomor_telepon, alamat) VALUE (:nomor_rm, :nama, :tanggal_lahir, :nomor_telepon, :alamat)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":nomor_rm" => $new_pasien["nomor_rm"],
                ":nama" => $new_pasien["nama"],
                ":tanggal_lahir" => $new_pasien["tanggal_lahir"],
                ":nomor_telepon" => $new_pasien["nomor_telepon"],
                ":alamat" => $new_pasien["alamat"],
            ];

            if ($stmt->execute($data)) {
                // get data last id
                $id = $this->db->lastInsertId();
                $sql = "SELECT * FROM pasien WHERE id =:id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":id" => $id]);
                $result = $stmt->fetch();
                return $response->withJson(
                    ["status" => "success", "data" => $result],
                    200
                );
            }
            return $response->withJson(
                ["status" => "failed", "data" => "error insert poli"],
                200
            );
        });

        // UPDATE a pasien by ID
        $app->put("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $new_pasien = $request->getParsedBody();
            $sql =
                "UPDATE pasien SET nomor_rm=:nomor_rm, nama=:nama, tanggal_lahir=:tanggal_lahir, nomor_telepon=:nomor_telepon, alamat=:alamat WHERE id=:id";
            $stmt = $this->db->prepare($sql);

            $data = [
                ":id" => $id,
                ":nomor_rm" => $new_pasien["nomor_rm"],
                ":nama" => $new_pasien["nama"],
                ":tanggal_lahir" => $new_pasien["tanggal_lahir"],
                ":nomor_telepon" => $new_pasien["nomor_telepon"],
                ":alamat" => $new_pasien["alamat"],
            ];
            if ($stmt->execute($data)) {
                // get response
                $sql = "SELECT * FROM pasien WHERE id =:id";
                $stmt_r = $this->db->prepare($sql);
                $stmt_r->execute([":id" => $id]);
                $result = $stmt_r->fetch();
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error Update Pasien",
                ],
                200
            );
        });

        // DELETE a pasien by ID
        $app->delete("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
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

            if ($stmt->execute($data)) {
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error hapus pasien",
                ],
                200
            );
        });

        // Search pasien by name
        $app->get("/search/", function (Request $request, Response $response) {
            $keyword = $request->getQueryParam("keyword");
            $sql = "SELECT * FROM pasien WHERE nama LIKE '%$keyword%'";
            $stmt = $this->db->prepare($sql);
            $data = [":nama" => $keyword];
            $stmt->execute($data);
            $result = $stmt->fetchAll();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });
    });

    // GROUP PEGAWAI
    $app->group("/pegawai", function (App $app) {
        // GET all pegawai
        $app->get("/", function (Request $request, Response $response) {
            $sql = "SELECT * FROM pegawai";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetchAll();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // GET a specific pegawai by ID
        $app->get("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $sql = "SELECT * FROM pegawai WHERE id =:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $mainCount = $stmt->rowCount();
            $result = $stmt->fetch();
            if ($mainCount == 0) {
                return $this->response->withJson(
                    [
                        "status" => "error",
                        "message" => "no result data.",
                    ],
                    200
                );
            }
            return $response->withJson(
                [
                    "status" => "success",
                    "data" => $result,
                ],
                200
            );
        });

        // POST a new pegawai
        $app->post("/", function (Request $request, Response $response) {
            $new_pegawai = $request->getParsedBody();
            $sql =
                "INSERT INTO pegawai (nip, nama, tanggal_lahir, nomor_telepon, email, password) VALUE (:nip, :nama, :tanggal_lahir, :nomor_telepon, :email, :password)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":nip" => $new_pegawai["nip"],
                ":nama" => $new_pegawai["nama"],
                ":tanggal_lahir" => $new_pegawai["tanggal_lahir"],
                ":nomor_telepon" => $new_pegawai["nomor_telepon"],
                ":email" => $new_pegawai["email"],
                ":password" => $new_pegawai["password"],
            ];

            if ($stmt->execute($data)) {
                // get data last id
                $id = $this->db->lastInsertId();
                $sql = "SELECT * FROM pegawai WHERE id =:id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":id" => $id]);
                $result = $stmt->fetch();
                return $response->withJson(
                    ["status" => "success", "data" => $result],
                    200
                );
            }
            return $response->withJson(
                ["status" => "failed", "data" => "error insert pegawai"],
                200
            );
        });

        // UPDATE data pegawai by ID
        $app->put("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            $new_pegawai = $request->getParsedBody();
            $sql =
                "UPDATE pegawai SET nip=:nip, nama=:nama, tanggal_lahir=:tanggal_lahir, nomor_telepon=:nomor_telepon, email=:email, password=:password WHERE id=:id";
            $stmt = $this->db->prepare($sql);

            $data = [
                ":id" => $id,
                ":nip" => $new_pegawai["nip"],
                ":nama" => $new_pegawai["nama"],
                ":tanggal_lahir" => $new_pegawai["tanggal_lahir"],
                ":nomor_telepon" => $new_pegawai["nomor_telepon"],
                ":email" => $new_pegawai["email"],
                ":password" => $new_pegawai["password"],
            ];
            if ($stmt->execute($data)) {
                // get response
                $sql = "SELECT * FROM pegawai WHERE id =:id";
                $stmt_r = $this->db->prepare($sql);
                $stmt_r->execute([":id" => $id]);
                $result = $stmt_r->fetch();
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error Update Pegawai",
                ],
                200
            );
        });

        // DELETE a pegawai by ID
        $app->delete("/{id}", function (
            Request $request,
            Response $response,
            $args
        ) {
            $id = $args["id"];
            // cari data
            $sql = "SELECT * FROM pegawai WHERE id =:id";
            $get_stmt = $this->db->prepare($sql);
            $get_stmt->execute([":id" => $id]);
            $result = $get_stmt->fetch();

            //hapus data
            $sql = "DELETE FROM pegawai WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $data = [":id" => $id];

            if ($stmt->execute($data)) {
                return $response->withJson(
                    [
                        "status" => "success",
                        "data" => $result,
                    ],
                    200
                );
            }

            return $response->withJson(
                [
                    "status" => "failed",
                    "data" => "error hapus pegawai",
                ],
                200
            );
        });

        // Search pegawai by name
        $app->get("/search/", function (Request $request, Response $response) {
            $keyword = $request->getQueryParam("keyword");
            $sql = "SELECT * FROM pegawai WHERE nama LIKE '%$keyword%'";
            $stmt = $this->db->prepare($sql);
            $data = [":nama" => $keyword];
            $stmt->execute($data);
            $result = $stmt->fetchAll();
            return $response->withJson(
                ["status" => "success", "data" => $result],
                200
            );
        });
    });
};
