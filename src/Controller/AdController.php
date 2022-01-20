<?php

namespace Src\Controller;

use PDO;
use Src\TableGateway\AdGateway;
use Throwable;

class AdController
{
    private string $requestMethod;
    private ?string $postfix;
    private AdGateway $adGateway;

    public function __construct(PDO $db, string $requestMethod, ?string $postfix)
    {
        $this->requestMethod = $requestMethod;
        $this->postfix = $postfix;
        $this->adGateway = new AdGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                try {
                    $response = $this->{$this->postfix}();
                } catch (Throwable $e) {
                    $response = $this->notFoundResponse();
                }

                break;
            case 'POST':
                if ($this->postfix) {
                    $response = $this->update((int)$this->postfix);
                } else {
                    $response = $this->create();
                }
                break;
            case 'DELETE':
                $response = $this->delete($this->postfix);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function relevant(): array
    {
        $result = $this->adGateway->relevant();

        if (! $result) {
            return $this->notFoundResponse();
        }

        $result['limit'] -= 1;
        $this->adGateway->update($result['id'], $result);
        unset($result['limit']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'OK',
            'code' => 200,
            'data' => $result,
        ]);

        return $response;
    }

    private function create(): array
    {
        $input = $_POST;

        if (! $this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }

        $id = $this->adGateway->insert($input);
        $data = $this->adGateway->find($id);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'OK',
            'code' => 200,
            'data' => $data,
        ]);

        return $response;
    }

    private function update($id): array
    {
        $result = $this->adGateway->find($id);

        if (! $result) {
            return $this->notFoundResponse();
        }

        $input = $_POST;

        if (! $this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }

        $this->adGateway->update($id, $input);
        $data = $this->adGateway->find($id);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'OK',
            'code' => 200,
            'data' => $data
        ]);

        return $response;
    }

    private function delete($id): array
    {
        $result = $this->adGateway->find($id);

        if (! $result) {
            return $this->notFoundResponse();
        }

        $this->adGateway->delete($id);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([]);

        return $response;
    }

    private function validate($input): bool
    {
        if (! isset($input['text'])) {
            return false;
        }

        if (! isset($input['price'])) {
            return false;
        }

        return true;
    }

    private function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'Invalid input',
            'code' => 400,
            'data' => json_encode([]),
        ]);

        return $response;
    }

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'Not found',
            'code' => 404,
            'data' => [],
        ]);

        return $response;
    }
}