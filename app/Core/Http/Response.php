<?php

namespace App\Core\Http;

class Response
{
    private array $headers = [];
    private string $content = '';
    private int $status = 200;

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        http_response_code($status);
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function json(array $data, int $status = 200): self
    {
        $this->status($status);
        $this->header('Content-Type', 'application/json');
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header(sprintf('%s: %s', $key, $value), true, $this->status);
        }

        echo $this->content;
    }
}
