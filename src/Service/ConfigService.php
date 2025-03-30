<?php

namespace App\Service;

use App\Entity\Config;
use App\Repository\ConfigRepository;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigService
{
    const TYPE_INT = 'int';
    const TYPE_BOOL = 'bool';
    const TYPE_FLOAT = 'float';
    const TYPE_TEXT = 'text';


    public const C_VAT = 'vat';
    public const C_QUESTIONS_CAN_ASK = 'questions_can_ask';

    protected $available_configs = [
        self::C_VAT => [
            'description' => 'PVM %',
            'default_value' => 21,
            'type' => self::TYPE_FLOAT,
        ],
        self::C_QUESTIONS_CAN_ASK => [
            'description' => 'Ar galima siųsti užduoti klausimą',
            'default_value' => true,
            'type' => self::TYPE_BOOL,
        ],
    ];

    protected ConfigRepository $configRepository;
    protected array $configs;
    /** @var array|Config[] */
    protected array $configsEntities;
    private TranslatorInterface $translator;

    public function __construct(ConfigRepository $configRepository, TranslatorInterface $translator)
    {
        $this->configRepository = $configRepository;
        $this->initConfigs();
        $this->translator = $translator;
    }

    protected function initConfigs(){

        $this->configs = [];
        $this->configsEntities = $this->configRepository->findAll();

        foreach ($this->available_configs as $key => $params){
            $this->configs[$key] = $params['default_value'] ?? null;
            foreach($this->configsEntities as $configEntity){
                if($configEntity->key === $key){
                    $v = $this->mapValue($params['type'] ?? null, $configEntity->value);
                    $allowNull = $params['allow_null'] ?? false;
                    if($v !== null || $allowNull){
                        $this->configs[$key] = $v;
                    }
                    break;
                }
            }
        }
    }

    public function getConfigs():array{
        return $this->configs;
    }

    public function getConfigValue(string $key, $default = null){
        return $this->configs[$key] ?? $default;
    }

    public function getConfigParams(string $key):?array{
        return $this->available_configs[$key] ?? null;
    }

    /**
     * Grazina pasirinkimus, kurie dar nenustatyti
     *
     * @return array
     */
    public function getNotSetChoices():array{
        $choices = [];

        foreach ($this->available_configs as $key => $params){
            foreach ($this->configsEntities as $configsEntity){
                if($configsEntity->key === $key) continue 2;
            }
            $choices[$params['description']] = $key;
        }

        return $choices;
    }
    public function hasNotSetChoices():bool{
        return !empty($this->getNotSetChoices());
    }

    public function mapValue(?string $type, $value){

        if($value !== null){
            switch ($type){
                case static::TYPE_BOOL:
                    return boolval($value);
                case static::TYPE_INT:
                    return intval($value);
                case static::TYPE_FLOAT:
                    return floatval($value);
                default:
                    return strval($value);
            }
        }

        return $value;
    }

    public function formatValue(?string $type, mixed $value):?string
    {
        $mappedValue = $this->mapValue($type, $value);

        if(is_null($mappedValue)) return null;
        elseif (is_bool($mappedValue)){
            return $this->translator->trans($mappedValue ? 'yes' : 'no');
        }else{
            return strval($mappedValue);
        }
    }

    public function addField(?string $type, string $propertyName = 'value'):FieldInterface
    {
        return match ($type){
            static::TYPE_BOOL => BooleanField::new($propertyName),
            default => TextField::new($propertyName)
        };
    }
}
