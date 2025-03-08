<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use SuareSu\PyrusClient\Entity\Attachment\Attachment;
use SuareSu\PyrusClient\Entity\Catalog\Catalog;
use SuareSu\PyrusClient\Entity\Catalog\CatalogCreate;
use SuareSu\PyrusClient\Entity\Catalog\CatalogHeader;
use SuareSu\PyrusClient\Entity\Catalog\CatalogItem;
use SuareSu\PyrusClient\Entity\Catalog\CatalogItemCreate;
use SuareSu\PyrusClient\Entity\Catalog\CatalogUpdate;
use SuareSu\PyrusClient\Entity\Catalog\CatalogUpdateResponse;
use SuareSu\PyrusClient\Entity\File\File;
use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Form\PrintForm;
use SuareSu\PyrusClient\Entity\Person\Person;
use SuareSu\PyrusClient\Entity\Task\Approval;
use SuareSu\PyrusClient\Entity\Task\Comment;
use SuareSu\PyrusClient\Entity\Task\FormTask;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreate;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;
use SuareSu\PyrusClient\Entity\Task\FormTaskField;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @psalm-api
 */
final class PyrusSerializer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FormTask
            || $data instanceof FormTaskCreateField
            || $data instanceof Comment
            || $data instanceof FormTaskField
            || $data instanceof FormTaskCreate
            || $data instanceof Approval
            || $data instanceof File
            || $data instanceof Attachment
            || $data instanceof PrintForm
            || $data instanceof Form
            || $data instanceof FormField
            || $data instanceof Person
            || $data instanceof CatalogUpdate
            || $data instanceof CatalogItem
            || $data instanceof CatalogCreate
            || $data instanceof Catalog
            || $data instanceof CatalogHeader
            || $data instanceof CatalogItemCreate
            || $data instanceof CatalogUpdateResponse;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        if ($data instanceof FormTask) {
            return $this->normalizeFormTask($data);
        } elseif ($data instanceof FormTaskCreateField) {
            return $this->normalizeFormTaskCreateField($data);
        } elseif ($data instanceof Comment) {
            return $this->normalizeComment($data);
        } elseif ($data instanceof FormTaskField) {
            return $this->normalizeFormTaskField($data);
        } elseif ($data instanceof FormTaskCreate) {
            return $this->normalizeFormTaskCreate($data);
        } elseif ($data instanceof Approval) {
            return $this->normalizeApproval($data);
        } elseif ($data instanceof File) {
            return $this->normalizeFile($data);
        } elseif ($data instanceof Attachment) {
            return $this->normalizeAttachment($data);
        } elseif ($data instanceof PrintForm) {
            return $this->normalizePrintForm($data);
        } elseif ($data instanceof Form) {
            return $this->normalizeForm($data);
        } elseif ($data instanceof FormField) {
            return $this->normalizeFormField($data);
        } elseif ($data instanceof Person) {
            return $this->normalizePerson($data);
        } elseif ($data instanceof CatalogUpdate) {
            return $this->normalizeCatalogUpdate($data);
        } elseif ($data instanceof CatalogItem) {
            return $this->normalizeCatalogItem($data);
        } elseif ($data instanceof CatalogCreate) {
            return $this->normalizeCatalogCreate($data);
        } elseif ($data instanceof Catalog) {
            return $this->normalizeCatalog($data);
        } elseif ($data instanceof CatalogHeader) {
            return $this->normalizeCatalogHeader($data);
        } elseif ($data instanceof CatalogItemCreate) {
            return $this->normalizeCatalogItemCreate($data);
        } elseif ($data instanceof CatalogUpdateResponse) {
            return $this->normalizeCatalogUpdateResponse($data);
        }

        throw new InvalidArgumentException("Can't normalize provided data");
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): bool {
        return FormTask::class === $type
            || FormTaskCreateField::class === $type
            || Comment::class === $type
            || FormTaskField::class === $type
            || FormTaskCreate::class === $type
            || Approval::class === $type
            || File::class === $type
            || Attachment::class === $type
            || PrintForm::class === $type
            || Form::class === $type
            || FormField::class === $type
            || Person::class === $type
            || CatalogUpdate::class === $type
            || CatalogItem::class === $type
            || CatalogCreate::class === $type
            || Catalog::class === $type
            || CatalogHeader::class === $type
            || CatalogItemCreate::class === $type
            || CatalogUpdateResponse::class === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!\is_array($data)) {
            throw new InvalidArgumentException("Can't denormalize provided data");
        }

        if (FormTask::class === $type) {
            return $this->denormalizeFormTask($data);
        } elseif (FormTaskCreateField::class === $type) {
            return $this->denormalizeFormTaskCreateField($data);
        } elseif (Comment::class === $type) {
            return $this->denormalizeComment($data);
        } elseif (FormTaskField::class === $type) {
            return $this->denormalizeFormTaskField($data);
        } elseif (FormTaskCreate::class === $type) {
            return $this->denormalizeFormTaskCreate($data);
        } elseif (Approval::class === $type) {
            return $this->denormalizeApproval($data);
        } elseif (File::class === $type) {
            return $this->denormalizeFile($data);
        } elseif (Attachment::class === $type) {
            return $this->denormalizeAttachment($data);
        } elseif (PrintForm::class === $type) {
            return $this->denormalizePrintForm($data);
        } elseif (Form::class === $type) {
            return $this->denormalizeForm($data);
        } elseif (FormField::class === $type) {
            return $this->denormalizeFormField($data);
        } elseif (Person::class === $type) {
            return $this->denormalizePerson($data);
        } elseif (CatalogUpdate::class === $type) {
            return $this->denormalizeCatalogUpdate($data);
        } elseif (CatalogItem::class === $type) {
            return $this->denormalizeCatalogItem($data);
        } elseif (CatalogCreate::class === $type) {
            return $this->denormalizeCatalogCreate($data);
        } elseif (Catalog::class === $type) {
            return $this->denormalizeCatalog($data);
        } elseif (CatalogHeader::class === $type) {
            return $this->denormalizeCatalogHeader($data);
        } elseif (CatalogItemCreate::class === $type) {
            return $this->denormalizeCatalogItemCreate($data);
        } elseif (CatalogUpdateResponse::class === $type) {
            return $this->denormalizeCatalogUpdateResponse($data);
        }

        throw new InvalidArgumentException("Can't denormalize provided type");
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            FormTask::class => true,
            FormTaskCreateField::class => true,
            Comment::class => true,
            FormTaskField::class => true,
            FormTaskCreate::class => true,
            Approval::class => true,
            File::class => true,
            Attachment::class => true,
            PrintForm::class => true,
            Form::class => true,
            FormField::class => true,
            Person::class => true,
            CatalogUpdate::class => true,
            CatalogItem::class => true,
            CatalogCreate::class => true,
            Catalog::class => true,
            CatalogHeader::class => true,
            CatalogItemCreate::class => true,
            CatalogUpdateResponse::class => true,
        ];
    }

    private function normalizeFormTask(FormTask $object): array
    {
        $result = [
            'id' => $object->id,
            'form_id' => $object->formId,
            'create_date' => $object->createDate->format('Y-m-d\TH:i:s\Z'),
            'last_modified_date' => $object->lastModifiedDate->format('Y-m-d\TH:i:s\Z'),
            'author' => $object->author,
            'close_date' => $object->closeDate->format('Y-m-d\TH:i:s\Z'),
            'approvals' => array_map(fn (Approval $val): array => $this->normalizeApproval($val), $object->approvals),
            'subscribers' => array_map(fn (Approval $val): array => $this->normalizeApproval($val), $object->subscribers),
            'linked_task_ids' => $object->linkedTaskIds,
            'attachments' => array_map(fn (Attachment $val): array => $this->normalizeAttachment($val), $object->attachments),
            'fields' => array_map(fn (FormTaskField $val): array => $this->normalizeFormTaskField($val), $object->fields),
            'comments' => array_map(fn (Comment $val): array => $this->normalizeComment($val), $object->comments),
        ];

        if (null !== $object->responsible) {
            $result['responsible'] = $object->responsible;
        }
        if (null !== $object->parentTaskId) {
            $result['parent_task_id'] = $object->parentTaskId;
        }

        return $result;
    }

    private function normalizeFormTaskCreateField(FormTaskCreateField $object): array
    {
        return [
            'id' => $object->id,
            'value' => $object->value,
        ];
    }

    private function normalizeComment(Comment $object): array
    {
        return [
            'id' => $object->id,
            'text' => $object->text,
            'create_date' => $object->createDate->format('Y-m-d\TH:i:s\Z'),
            'author' => $object->author,
        ];
    }

    private function normalizeFormTaskField(FormTaskField $object): array
    {
        return [
            'id' => $object->id,
            'type' => $object->type,
            'name' => $object->name,
            'code' => $object->code,
            'value' => $object->value,
        ];
    }

    private function normalizeFormTaskCreate(FormTaskCreate $object): array
    {
        $result = [
            'form_id' => $object->formId,
            'fields' => array_map(fn (FormTaskCreateField $val): array => $this->normalizeFormTaskCreateField($val), $object->fields),
            'attachments' => $object->attachments,
            'subscribers' => $object->subscribers,
            'list_ids' => $object->listIds,
            'approvals' => $object->approvals,
            'fill_defaults' => $object->fillDefaults,
        ];

        if (null !== $object->dueDate) {
            $result['due_date'] = $object->dueDate->format('Y-m-d\TH:i:s\Z');
        }
        if (null !== $object->due) {
            $result['due'] = $object->due;
        }
        if (null !== $object->duration) {
            $result['duration'] = $object->duration;
        }
        if (null !== $object->parentTaskId) {
            $result['parent_task_id'] = $object->parentTaskId;
        }
        if (null !== $object->scheduledDate) {
            $result['scheduled_date'] = $object->scheduledDate->format('Y-m-d\TH:i:s\Z');
        }
        if (null !== $object->scheduledDatetimeUtc) {
            $result['scheduled_datetime_utc'] = $object->scheduledDatetimeUtc->format('Y-m-d\TH:i:s\Z');
        }

        return $result;
    }

    private function normalizeApproval(Approval $object): array
    {
        return [
            'person' => $object->person,
            'approval_choice' => $object->approvalChoice,
        ];
    }

    private function normalizeFile(File $object): array
    {
        return [
            'guid' => $object->guid,
            'md5_hash' => $object->md5Hash,
        ];
    }

    private function normalizeAttachment(Attachment $object): array
    {
        return [
            'id' => $object->id,
            'name' => $object->name,
            'size' => $object->size,
            'md5' => $object->md5,
            'url' => $object->url,
            'version' => $object->version,
            'root_id' => $object->rootId,
        ];
    }

    private function normalizePrintForm(PrintForm $object): array
    {
        return [
            'print_form_id' => $object->printFormId,
            'print_form_name' => $object->printFormName,
        ];
    }

    private function normalizeForm(Form $object): array
    {
        return [
            'id' => $object->id,
            'name' => $object->name,
            'deleted_or_closed' => $object->deletedOrClosed,
            'steps' => $object->steps,
            'fields' => array_map(fn (FormField $val): array => $this->normalizeFormField($val), $object->fields),
            'print_forms' => array_map(fn (PrintForm $val): array => $this->normalizePrintForm($val), $object->printForms),
        ];
    }

    private function normalizeFormField(FormField $object): array
    {
        return [
            'id' => $object->id,
            'type' => $object->type->value,
            'name' => $object->name,
            'tooltip' => $object->tooltip,
            'info' => $object->info,
        ];
    }

    private function normalizePerson(Person $object): array
    {
        return [
            'id' => $object->id,
            'first_name' => $object->firstName,
            'last_name' => $object->lastName,
            'email' => $object->email,
            'type' => $object->type,
        ];
    }

    private function normalizeCatalogUpdate(CatalogUpdate $object): array
    {
        return [
            'apply' => $object->apply,
            'catalog_headers' => $object->catalogHeaders,
            'items' => array_map(fn (CatalogItemCreate $val): array => $this->normalizeCatalogItemCreate($val), $object->items),
        ];
    }

    private function normalizeCatalogItem(CatalogItem $object): array
    {
        return [
            'item_id' => $object->itemId,
            'values' => $object->values,
        ];
    }

    private function normalizeCatalogCreate(CatalogCreate $object): array
    {
        return [
            'name' => $object->name,
            'catalog_headers' => $object->catalogHeaders,
            'items' => array_map(fn (CatalogItemCreate $val): array => $this->normalizeCatalogItemCreate($val), $object->items),
        ];
    }

    private function normalizeCatalog(Catalog $object): array
    {
        return [
            'catalog_id' => $object->catalogId,
            'name' => $object->name,
            'source_type' => $object->sourceType,
            'version' => $object->version,
            'deleted' => $object->deleted,
            'supervisors' => $object->supervisors,
            'catalog_headers' => array_map(fn (CatalogHeader $val): array => $this->normalizeCatalogHeader($val), $object->catalogHeaders),
            'items' => array_map(fn (CatalogItem $val): array => $this->normalizeCatalogItem($val), $object->items),
        ];
    }

    private function normalizeCatalogHeader(CatalogHeader $object): array
    {
        return [
            'name' => $object->name,
            'type' => $object->type,
        ];
    }

    private function normalizeCatalogItemCreate(CatalogItemCreate $object): array
    {
        return [
            'values' => $object->values,
        ];
    }

    private function normalizeCatalogUpdateResponse(CatalogUpdateResponse $object): array
    {
        return [
            'apply' => $object->apply,
            'added' => array_map(fn (CatalogItemCreate $val): array => $this->normalizeCatalogItemCreate($val), $object->added),
            'deleted' => array_map(fn (CatalogItemCreate $val): array => $this->normalizeCatalogItemCreate($val), $object->deleted),
            'catalog_headers' => array_map(fn (CatalogHeader $val): array => $this->normalizeCatalogHeader($val), $object->catalogHeaders),
        ];
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFormTask(array $data): FormTask
    {
        return new FormTask(
            (int) ($data['id'] ?? 0),
            (int) ($data['form_id'] ?? 0),
            new \DateTimeImmutable((string) ($data['create_date'] ?? '')),
            new \DateTimeImmutable((string) ($data['last_modified_date'] ?? '')),
            $this->denormalizePerson((array) ($data['author'] ?? [])),
            new \DateTimeImmutable((string) ($data['close_date'] ?? '')),
            isset($data['responsible']) ? $this->denormalizePerson((array) ($data['responsible'] ?? [])) : null,
            array_map(fn (array $val): Approval => $this->denormalizeApproval($val), (array) ($data['approvals'] ?? [])),
            array_map(fn (array $val): Approval => $this->denormalizeApproval($val), (array) ($data['subscribers'] ?? [])),
            isset($data['parent_task_id']) ? (int) ($data['parent_task_id'] ?? 0) : null,
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['linked_task_ids'] ?? [])),
            array_map(fn (array $val): Attachment => $this->denormalizeAttachment($val), (array) ($data['attachments'] ?? [])),
            array_map(fn (array $val): FormTaskField => $this->denormalizeFormTaskField($val), (array) ($data['fields'] ?? [])),
            array_map(fn (array $val): Comment => $this->denormalizeComment($val), (array) ($data['comments'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFormTaskCreateField(array $data): FormTaskCreateField
    {
        return new FormTaskCreateField(
            (int) ($data['id'] ?? 0),
            $data['value'] ?? null,
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeComment(array $data): Comment
    {
        return new Comment(
            (int) ($data['id'] ?? 0),
            (string) ($data['text'] ?? ''),
            new \DateTimeImmutable((string) ($data['create_date'] ?? '')),
            $this->denormalizePerson((array) ($data['author'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFormTaskField(array $data): FormTaskField
    {
        return new FormTaskField(
            (int) ($data['id'] ?? 0),
            (string) ($data['type'] ?? ''),
            (string) ($data['name'] ?? ''),
            (string) ($data['code'] ?? ''),
            $data['value'] ?? null,
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFormTaskCreate(array $data): FormTaskCreate
    {
        return new FormTaskCreate(
            (int) ($data['form_id'] ?? 0),
            array_map(fn (array $val): FormTaskCreateField => $this->denormalizeFormTaskCreateField($val), (array) ($data['fields'] ?? [])),
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['attachments'] ?? [])),
            isset($data['due_date']) ? new \DateTimeImmutable((string) ($data['due_date'] ?? '')) : null,
            isset($data['due']) ? (string) ($data['due'] ?? '') : null,
            isset($data['duration']) ? (int) ($data['duration'] ?? 0) : null,
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['subscribers'] ?? [])),
            isset($data['parent_task_id']) ? (int) ($data['parent_task_id'] ?? 0) : null,
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['list_ids'] ?? [])),
            isset($data['scheduled_date']) ? new \DateTimeImmutable((string) ($data['scheduled_date'] ?? '')) : null,
            isset($data['scheduled_datetime_utc']) ? new \DateTimeImmutable((string) ($data['scheduled_datetime_utc'] ?? '')) : null,
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['approvals'] ?? [])),
            (bool) ($data['fill_defaults'] ?? false),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeApproval(array $data): Approval
    {
        return new Approval(
            $this->denormalizePerson((array) ($data['person'] ?? [])),
            (string) ($data['approval_choice'] ?? ''),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFile(array $data): File
    {
        return new File(
            (string) ($data['guid'] ?? ''),
            (string) ($data['md5_hash'] ?? ''),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeAttachment(array $data): Attachment
    {
        return new Attachment(
            (int) ($data['id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (int) ($data['size'] ?? 0),
            (string) ($data['md5'] ?? ''),
            (string) ($data['url'] ?? ''),
            (int) ($data['version'] ?? 0),
            (int) ($data['root_id'] ?? 0),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizePrintForm(array $data): PrintForm
    {
        return new PrintForm(
            (int) ($data['print_form_id'] ?? 0),
            (string) ($data['print_form_name'] ?? ''),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeForm(array $data): Form
    {
        return new Form(
            (int) ($data['id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (bool) ($data['deleted_or_closed'] ?? false),
            array_map(fn (mixed $val): string => (string) $val, (array) ($data['steps'] ?? [])),
            array_map(fn (array $val): FormField => $this->denormalizeFormField($val), (array) ($data['fields'] ?? [])),
            array_map(fn (array $val): PrintForm => $this->denormalizePrintForm($val), (array) ($data['print_forms'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeFormField(array $data): FormField
    {
        return new FormField(
            (int) ($data['id'] ?? 0),
            FormFieldType::from((string) ($data['type'] ?? '')),
            (string) ($data['name'] ?? ''),
            (string) ($data['tooltip'] ?? ''),
            (array) ($data['info'] ?? []),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizePerson(array $data): Person
    {
        return new Person(
            (int) ($data['id'] ?? 0),
            (string) ($data['first_name'] ?? ''),
            (string) ($data['last_name'] ?? ''),
            (string) ($data['email'] ?? ''),
            (string) ($data['type'] ?? ''),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogUpdate(array $data): CatalogUpdate
    {
        return new CatalogUpdate(
            (bool) ($data['apply'] ?? false),
            array_map(fn (mixed $val): string => (string) $val, (array) ($data['catalog_headers'] ?? [])),
            array_map(fn (array $val): CatalogItemCreate => $this->denormalizeCatalogItemCreate($val), (array) ($data['items'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogItem(array $data): CatalogItem
    {
        return new CatalogItem(
            (int) ($data['item_id'] ?? 0),
            array_map(fn (mixed $val): string => (string) $val, (array) ($data['values'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogCreate(array $data): CatalogCreate
    {
        return new CatalogCreate(
            (string) ($data['name'] ?? ''),
            array_map(fn (mixed $val): string => (string) $val, (array) ($data['catalog_headers'] ?? [])),
            array_map(fn (array $val): CatalogItemCreate => $this->denormalizeCatalogItemCreate($val), (array) ($data['items'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalog(array $data): Catalog
    {
        return new Catalog(
            (int) ($data['catalog_id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (string) ($data['source_type'] ?? ''),
            (int) ($data['version'] ?? 0),
            (bool) ($data['deleted'] ?? false),
            array_map(fn (mixed $val): int => (int) $val, (array) ($data['supervisors'] ?? [])),
            array_map(fn (array $val): CatalogHeader => $this->denormalizeCatalogHeader($val), (array) ($data['catalog_headers'] ?? [])),
            array_map(fn (array $val): CatalogItem => $this->denormalizeCatalogItem($val), (array) ($data['items'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogHeader(array $data): CatalogHeader
    {
        return new CatalogHeader(
            (string) ($data['name'] ?? ''),
            (string) ($data['type'] ?? ''),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogItemCreate(array $data): CatalogItemCreate
    {
        return new CatalogItemCreate(
            array_map(fn (mixed $val): string => (string) $val, (array) ($data['values'] ?? [])),
        );
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function denormalizeCatalogUpdateResponse(array $data): CatalogUpdateResponse
    {
        return new CatalogUpdateResponse(
            (bool) ($data['apply'] ?? false),
            array_map(fn (array $val): CatalogItemCreate => $this->denormalizeCatalogItemCreate($val), (array) ($data['added'] ?? [])),
            array_map(fn (array $val): CatalogItemCreate => $this->denormalizeCatalogItemCreate($val), (array) ($data['deleted'] ?? [])),
            array_map(fn (array $val): CatalogHeader => $this->denormalizeCatalogHeader($val), (array) ($data['catalog_headers'] ?? [])),
        );
    }
}
