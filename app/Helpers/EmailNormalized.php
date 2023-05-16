<?php

namespace App\Helpers;

class EmailNormalized
{
    protected string $emailId;
    protected \DateTime $date;
    protected string $sender;
    protected string $header;
    protected string $fromAddress;
    protected string $subject;
    protected string|null $content;
    protected string|null $textPlain;


    protected bool $hasAttachments;
    protected array $attachments;

    /**
     * @param string $emailId
     * @param \DateTime $date
     * @param string $sender
     * @param string $header
     * @param string $fromAddress
     * @param string $subject
     * @param string|null $content
     * @param bool $hasAttachments
     * @param array $attachments
     * @param string|null $textPlain
     */
    public function __construct(string $emailId, \DateTime $date, string $sender, string $header, string $fromAddress, string $subject, bool $hasAttachments, array $attachments, string|null $content = null, string|null $textPlain = null)
    {
        $this->emailId = $emailId;
        $this->date = $date;
        $this->sender = $sender;
        $this->header = $header;
        $this->fromAddress = $fromAddress;
        $this->subject = $subject;
        $this->content = $content;
        $this->textPlain = $textPlain;
        $this->hasAttachments = $hasAttachments;
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getEmailId(): string
    {
        return $this->emailId;
    }

    /**
     * @param string $emailId
     */
    public function setEmailId(string $emailId): void
    {
        $this->emailId = $emailId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getFromAddress(): string
    {
        return $this->fromAddress;
    }

    /**
     * @param string $fromAddress
     */
    public function setFromAddress(string $fromAddress): void
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    /**
     * @return string
     */
    public function getTextPlain(): string
    {
        return $this->textPlain;
    }

    /**
     * @param string $textPlain
     */
    public function setTextPlain(string $textPlain): void
    {
        $this->textPlain = $textPlain;
    }

    /**
     * @return bool
     */
    public function HasAttachments(): bool
    {
        return $this->hasAttachments;
    }

    /**
     * @param bool $hasAttachements
     */
    public function setHasAttachments(bool $hasAttachements): void
    {
        $this->hasAttachments = $hasAttachements;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }


}
