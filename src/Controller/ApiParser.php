<?php


namespace Frankspress\SgParserBundle\Controller;


use Frankspress\SgParserBundle\Attachment\NewAttachment;
use Frankspress\SgParserBundle\Email\NewEmail;
use Frankspress\SgParserBundle\Event\ParserApiCompleteEvent;
use Frankspress\SgParserBundle\Event\SgParserEvents;
use Frankspress\SgParserBundle\Handler\EmailHandler;
use Frankspress\SgParserBundle\Handler\UploadHandler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiParser
{

    private $request;
    private $emailHandler;
    private $newEmail;
    private $newAttachment;
    private $eventDispatcher;
    private $saveAttachment;
    private $uploadHandler;

    public function __construct( EmailHandler $emailHandler, UploadHandler $uploadHandler, RequestStack $request, NewEmail $newEmail, NewAttachment $newAttachment ,EventDispatcherInterface $eventDispatcher, bool $saveAttachment )
    {

        $this->request = $request;
        $this->emailHandler = $emailHandler;
        $this->newEmail = $newEmail;
        $this->newAttachment = $newAttachment;
        $this->eventDispatcher = $eventDispatcher;
        $this->saveAttachment = $saveAttachment;
        $this->uploadHandler = $uploadHandler;

    }

    public function sgParser() {

        $milliseconds = round(microtime(true) * 1000);

        $newEmail = $this->emailHandler->parseEmail();
        $newAttachment = [];

        if ( $this->saveAttachment ) {
           $newAttachment = $this->uploadHandler->addAttachment();
        }

        if ( $this->eventDispatcher ) {
            $event = new ParserApiCompleteEvent( $newEmail, $newAttachment );
            $this->eventDispatcher->dispatch( $event,SgParserEvents::PARSER_API );
        }

        $milliseconds2 = round(microtime(true) * 1000);
     //   file_put_contents(__DIR__."/timeee.txt",  'time init:'. $milliseconds.' time done:'.$milliseconds2  );
        return new JsonResponse(['success'], 200);
    }



}