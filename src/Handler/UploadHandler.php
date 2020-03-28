<?php


namespace Frankspress\SgParserBundle\Handler;


use Frankspress\SgParserBundle\Attachment\NewAttachment;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\File;

class UploadHandler
{

    private $validator;
    private $request;

    private $phpInjectionCheck;
    private $maxSize;
    private $mimeTypes =  [];

    private $violationMessage;



    public function __construct(ValidatorInterface $validator, RequestStack $request, bool $phpInjectionCheck, string $maxSize, array $mimeTypes)
    {
        $this->validator = $validator;
        $this->request = $request;
        $this->phpInjectionCheck = $phpInjectionCheck;
        $this->maxSize = $maxSize;
        $this->mimeTypes = $mimeTypes;

    }

    private function phpInjectionCheck( File $file = null )
    {

        if ($this->phpInjectionCheck) {
            $position = strpos(file_get_contents($file->getPathname()), '<?php');

            if ($position !== false) {
                $this->violationMessage = "Php Injection attempt.";
                return false;
            }
        }
        return true;
    }

    private function fileValidator(File $file = null )
    {
        $violation = $this->validator->validate( $file, [

                new \Symfony\Component\Validator\Constraints\File(
                    [   'maxSize'=> $this->maxSize,
                        'mimeTypes' => $this->mimeTypes,
                        'disallowEmptyMessage' => 'File is empty.'
                    ])
            ]
        );

        if ( $violation->count() > 0) {
            /** @var ConstraintViolation $error */
            $error = $violation[0];
            $this->violationMessage = $error->getMessage();

            return false;
        }
        return true;
    }

    public function addAttachment() {

        $attachmentContainer = [];
        $attachmentList =   json_decode( $this->request->getCurrentRequest()->get('attachment-info'));


        if ( empty($attachmentList) ) {
            return $attachmentContainer;
        }

        foreach ( $attachmentList as $key => $fileInfo ) {
            /** @var File $file */
            $attachment = new NewAttachment();
            $file = $this->request->getCurrentRequest()->files->get( $key );

            if ( ! $this->fileValidator($file) || ! $this->phpInjectionCheck($file) ) {
                $attachment->setError( $this->violationMessage );
            }
            if ( isset( $file ) ) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $attachment->setFileName( $fileName . '_' . uniqid() . '.' . $file->guessExtension());
                $attachment->setOriginalFileName( $fileName . '.' . $file->guessExtension());
                $attachment->setType($file->guessExtension());
                $attachment->setFilePath($file->getPathname());
                $attachment->setSize($file->getSize());
            }

            $attachmentContainer [] = $attachment;
        }

        return $attachmentContainer;

    }
}