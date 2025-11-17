<?php
namespace App\Data;


class ATOmeta
{
    public function __construct(
        public ?string $application_date = null,
        public ?string $application_type = null,
        public ?string $corporate_name = null,
        public ?string $file_uploaded = null,
        public ?string $office_address = null,
        public ?string $owner_email = null,
        public ?string $owner_mobile = null,
        public ?string $owner_name = null,
        public ?string $representative_email = null,
        public ?string $representative_mobile = null,
        public ?string $trade_name = null,
    ) {}

    // ➤ Convert object → array
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    // ➤ Create object from array
    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }
}