<?php

namespace Frankspress\SgParserBundle\Event;


use Frankspress\SgParserBundle\Email\NewEmail;

class ParserApiCompleteEvent
{

    private $newEmail;
    private $newAttachment = [];

    public function __construct( $newEmail, $newAttachment )
    {
        $this->newEmail = $newEmail;
        $this->newAttachment = $newAttachment;
    }

    /**
     * @return NewEmail
     */
    public function getNewEmail(): NewEmail
    {
        return $this->newEmail;
    }

    /**
     * @param NewEmail
     */
    public function setNewEmail($newEmail): void
    {
        $this->newEmail = $newEmail;
    }

    /**
     * @return array|null
     */
    public function getNewAttachment():? array
    {
        return $this->newAttachment;
    }

    /**
     * @param array
     */
    public function setNewAttachment(array $newAttachment): void
    {
        $this->newAttachment = $newAttachment;
    }






}