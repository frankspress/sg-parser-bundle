# SgParserBundle
Parses and validates SendGrid email/attachments sent to the WebHook ( Symfony 5 )

Requirements
------------

  * PHP 7.1.3 or higher;
  * Symfony 5.*.

Installation
------------

```sh
composer require frankspress/sg-parser-bundle
```

Usage
------------
Create a new route and set the prefix to configure your new webhook point.
```yaml
# app/routes/sg_api_parser.yml

sg_api_parsers:
    resource: '@FrankspressSgParserBundle/Resources/config/routes.xml'
    prefix: '/api'
    trailing_slash_on_root: false
```

Create a new EventSubscriber. This will automatically be called when a new email is sent to your Api point.

```php
namespace App\EventSubscriber;

use Frankspress\SgParserBundle\Attachment\NewAttachment;
use Frankspress\SgParserBundle\Event\ParserApiCompleteEvent;
use Frankspress\SgParserBundle\Event\SgParserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SgParseEmail implements EventSubscriberInterface
{
    
    public static function getSubscribedEvents()
    {
        return [
                    SgParserEvents::PARSER_API => 'onParserApiComplete'
               ];
    }

    public function onParserApiComplete(ParserApiCompleteEvent $event )
    {
        $email = $event->getNewEmail();
        $attachments = $event->getNewAttachment();

        // SAVE YOUR EMAIL/ATTACHMENTS

    }
}
```

#### Default Config
To modify any of the default parameters create a config file and change any of the following. 
For instance, if you want the bundle to allow only specific mime types then you can list them in `mime_types`.



```yaml
# app/config/sg_parser.yml

frankspress_sg_parser:

  email:

    max_body_length: 6000
    
    # Returns the email body or response body without the reply history and tags.
    raw_response: false
    
    # Returns the subject without RE:, FWD: etc
    raw_subject: false

  attachment:
    # Enables or disables attachment.
    handle_attachment: true
    
    # Searches for php injection in every attachment.
    php_injection: false

    file_upload_size: '6M'

    # Lists the mime types allowed. If empty or commented out "all" mime types will be allowed.
    # mime_types: [  'image/*',
    #                'application/pdf',
    #                'application/msword',
    #                'text/plain'
    # ]


```

## Basic Example

```php
  // ...
  public function onParserApiComplete(ParserApiCompleteEvent $event )
  {
    $email = $event->getNewEmail();
    $attachments = $event->getNewAttachment();

   /* 
    $email->getAttachment();
    $email->getSubject();
    $email->getSenderEmail();
    $email->getSenderName();
    $email->getBody(); 
    */
    if ( !empty($email->getAttachment() ) ) {

      foreach ($attachments as $attachment ) {
        /*
        $attachment->getFile();
        $attachment->getFileName();
        $attachment->getOriginalFileName();
        $attachment->error();
        $attachment->getFilePath();
        $attachment->getSize();
        $attachment->getType();
        */
      }
    }

  }
```
Methods are pretty much self explanatory. `$email->getAttachments()` returns an array with the attachment titles not the actual attachments.

`$attachments` comes as an array of attachments. From the example above you can see that for each attachment you can load its actual file with `$attachment->getFile()` or get it's filename with `$attachment->getFileName()`. One important note about `$attachment->error()`, if this is empty the file has passed validation, if it's not empty then only the first encountered error will be recorded.

