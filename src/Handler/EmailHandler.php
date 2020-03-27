<?php


namespace Frankspress\SgParserBundle\Handler;


use Frankspress\SgParserBundle\Email\NewEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailHandler
{


    private $validator;
    private $request;
    private $maxBodyLength;
    private $rawResponse;
    private $rawSubject;

    public function __construct(ValidatorInterface $validator, RequestStack $request, int $maxBodyLength, bool $rawResponse, bool $rawSubject )
    {
        $this->validator = $validator;
        $this->request = $request;
        $this->maxBodyLength = $maxBodyLength;
        $this->rawResponse = $rawResponse;
        $this->rawSubject = $rawSubject;
    }

    private function getEmail($senderData = '') {
        $senderData = explode(' <', trim($senderData, '> '));
        return !empty($senderData[1]) ? $senderData[1] : '';
    }

    private function getName($senderData = '') {
        $senderData = explode(' <', trim($senderData, '> '));
        return !empty($senderData[0]) ? $senderData[0] : '';
    }

    public function validateEmail($email) {
        $errors = $this->validator->validate($email);
        if (count($errors) > 0) {
            return false;
        }
        return true;
    }

    /** @return NewEmail
     * @var NewEmail $newEmail
     */
    public function parseEmail() {

        $newEmail = new NewEmail();

        /** Fetch components*/
        $message = !empty($this->request->getCurrentRequest()->get('text')) ? $this->request->getCurrentRequest()->get('text') : '';
        $subject = !empty($this->request->getCurrentRequest()->get('subject')) ? $this->request->getCurrentRequest()->get('subject') : '' ;
        $senderData = !empty($this->request->getCurrentRequest()->get('from')) ? $this->request->getCurrentRequest()->get('from') : '' ;

        /** Extracts received message from Email body */
        if (! $this->rawResponse ) {
            $message = preg_replace('#(^\w.+:\n)?(^>.*(\n|$))+#mi', "", $message); // Gmail strip
            $pos = strpos($message, "________") ? strpos($message, "________") : strlen($message);
            $message = substr($message, 0, $pos);  // Outlook strip

            $message = trim(substr($message, 0, $this->maxBodyLength));
        }
        /** Extracts Subject */
        if (! $this->rawSubject ) {
            $subject = trim(preg_replace("/([\[\(] *)?(RE?S?|FYI|RIF|I|FS|VB|RV|ENC|ODP|PD|YNT|ILT|SV|VS|VL|AW|WG|ΑΠ|ΣΧΕΤ|ΠΡΘ|תגובה|הועבר|主题|转发|FWD?) *([-:;)\]][ :;\])-]*|$)|\]+ *$/im", "", $subject)); // RE / FWD Strip
        }

        /** Set Email Entity*/
        $newEmail->setSenderEmail($this->getEmail($senderData));
        $newEmail->setSenderName($this->getName($senderData));
        $newEmail->setSubject($subject);
        $newEmail->setBody($message);

        /** Generate attachment list*/
        $attachmentList = json_decode( $this->request->getCurrentRequest()->get('attachment-info'), TRUE );
        if (!empty( $attachmentList )) {
            foreach ($attachmentList as $attachment => $content) {
                $newEmail->addAttachment($content['filename']);
            }
        }

        return $newEmail;
    }


}