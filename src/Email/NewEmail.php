<?php


namespace Frankspress\SgParserBundle\Email;


class NewEmail
{
    private $senderEmail;
    private $senderName;
    private $subject;
    private $body;
    private $attachment = [];

    /**
     * @return string
     */
    public function getSenderEmail():? string
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return string
     */
    public function getSenderName():? string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getSubject():? string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody():? string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getAttachment(): array
    {
        return $this->attachment;
    }

    /**
     * @param array $attachment
     */
    public function addAttachment(string $attachment): void
    {
        $this->attachment []= $attachment;
    }



}