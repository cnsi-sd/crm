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
     * @return string
     */
    public function getEmailId(): string
    {
        return $this->emailId;
    }

    /**
     * @param string $emailId
     * @return EmailNormalized
     */
    public function setEmailId(string $emailId): EmailNormalized
    {
        $this->emailId = $emailId;
        return $this;
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
     * @return EmailNormalized
     */
    public function setDate(\DateTime $date): EmailNormalized
    {
        $this->date = $date;
        return $this;
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
     * @return EmailNormalized
     */
    public function setSender(string $sender): EmailNormalized
    {
        $this->sender = $sender;
        return $this;
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
     * @return EmailNormalized
     */
    public function setHeader(string $header): EmailNormalized
    {
        $this->header = $header;
        return $this;
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
     * @return EmailNormalized
     */
    public function setFromAddress(string $fromAddress): EmailNormalized
    {
        $this->fromAddress = $fromAddress;
        return $this;
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
     * @return EmailNormalized
     */
    public function setSubject(string $subject): EmailNormalized
    {
        $this->subject = $subject;
        return $this;
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
     * @return EmailNormalized
     */
    public function setContent(string $content): EmailNormalized
    {
        $this->content = $content;
        return $this;
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
     * @return EmailNormalized
     */
    public function setTextPlain(string $textPlain): EmailNormalized
    {
        $this->textPlain = $textPlain;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAttachments(): bool
    {
        return $this->hasAttachments;
    }

    /**
     * @param bool $hasAttachements
     * @return EmailNormalized
     */
    public function setHasAttachments(bool $hasAttachements): EmailNormalized
    {
        $this->hasAttachments = $hasAttachements;
        return $this;
    }

    /**
     * @return EmailAttachementNormalized[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     * @return EmailNormalized
     */
    public function setAttachments(array $attachments): EmailNormalized
    {
        $this->attachments = $attachments;
        return $this;
    }


}
