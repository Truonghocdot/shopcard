<?php

namespace App\Types;

class ServiceResult
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public mixed $data = null,
        public ?\Throwable $exception = null
    ) {}

    public static function success(mixed $data = null, ?string $message = null): self
    {
        return new self(true, $message, $data);
    }

    public static function error(?string $message = null, mixed $data = null, ?\Throwable $exception = null): self
    {
        return new self(false, $message, $data, $exception);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isError(): bool
    {
        return !$this->success;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getException(): ?\Throwable
    {
        return $this->exception;
    }
}
