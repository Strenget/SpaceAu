<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 30.12.2018
 * Time: 23:13
 */


use Nette\Application\ApplicationException;


class TextManager
{

    public $registrationText;
    public $loginText;

    /**
     * TextManager constructor.
     * @param string $locale
     * @throws ApplicationException
     */
    public function __construct($locale = 'cs')
    {
        $this->setTextByLocale($locale);
    }

    /**
     * @param $locale
     * @throws ApplicationException
     */
    public function setTextByLocale($locale)
    {
        $localizationMethos = 'set' . ucfirst($locale);
        if(method_exists($this, $localizationMethos)) {
            call_user_func([$this, $localizationMethos]);
        } else {
            throw new ApplicationException(sprintf('Missing requested %s localization.', __class__));
        }
    }

    private function setEn()
    {
        $this->registrationText = [
            1 => 'Registration',
            2 => 'Password',
            3 => 'Please enter email',
            4 => 'Please enter password',
            5 => 'Password (verify)',
            6 => 'Please enter password for verification',
            7 => 'Password verification failed. Passwords do not match',
            8 => 'Register',
            9 => 'Timeout try again'
        ];

    }

    private function setCs()
    {
        $this->registrationText = [
            1 => 'Registrace',
            2 => 'Heslo',
            3 => 'Napiště svůj email prosím',
            4 => 'Napiště heslo prosím',
            5 => 'Heslo (verifikace)',
            6 => 'Napiště ještě jednou heslo pro kontrolu',
            7 => 'Nespravné heslo. Hesla neshoduji',
            8 => 'Registrace',
            9 => 'Vyprsl časový limit, zkuste znovu'
        ];
    }


}