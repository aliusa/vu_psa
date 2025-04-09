<?php

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Service\MailerManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseController extends AbstractController
{
    /** @var ParameterBag */
    protected $requestParameters;
    protected string $baseUrl;
    protected Request $request;
    private ?Application $application = null;

    public function __construct(
        protected TranslatorInterface $translator,
        RequestStack $requestStack,
        protected MailerManager $mailerManager,
        protected ManagerRegistry $managerRegistry,
        protected KernelInterface $kernel,
    )
    {
        $this->request = $requestStack->getCurrentRequest();

        $this->requestParameters = Request::createFromGlobals()->request;
        $this->baseUrl = Request::createFromGlobals()->getScheme() . '://' . Request::createFromGlobals()->getHttpHost();
    }

    //region parameters
    //region GET parameters
    protected function hasQueryParameter(string $parameter): bool
    {
        return Request::createFromGlobals()->query->has($parameter);
    }
    protected function getQueryParameter(string $parameter)
    {
        return Request::createFromGlobals()->query->get($parameter);
    }
    protected function getQueryParameters()
    {
        return Request::createFromGlobals()->query->all();
    }
    //endregion GET parameters
    //region POST parameters
    protected function hasPostParameter(string $parameter): bool
    {
        return Request::createFromGlobals()->request->has($parameter);
    }
    protected function getPostParameter(string $parameter)
    {
        return Request::createFromGlobals()->request->get($parameter);
    }
    protected function getPostParameters()
    {
        return Request::createFromGlobals()->request->all();
    }
    //endregion POST parameters
    //region combined parameters
    protected function hasRequestParameter(string $parameter): bool
    {
        return $this->hasQueryParameter($parameter) || $this->hasPostParameter($parameter);
    }

    /**
     * Ieško POST parametruose, poto ieško GET parametruose.
     * @param  string  $parameter
     * @return mixed|null
     */
    protected function getRequestParameter(string $parameter)
    {
        if ($this->hasPostParameter($parameter)) {
            return $this->getPostParameter($parameter);
        }
        if ($this->hasQueryParameter($parameter)) {
            return $this->getQueryParameter($parameter);
        }
        return null;
    }
    protected function getRequestParameters(): array
    {
        return array_merge($this->getQueryParameters(), $this->getPostParameters());
    }
    //endregion combined parameters
    //endregion parameters

    //region random validate

    /**
     * Helper to get get form validation errors
     *
     * @param  FormInterface  $form
     * @param  TranslatorInterface  $translator
     * @return array
     */
    protected function getValidationErrors(FormInterface $form, TranslatorInterface $translator): array
    {
        $errors = [];
        foreach ($form->all() as $key => $field) {
            foreach ($field->getErrors() as $error) {
                $errors[$key][] = $translator->trans($error->getMessage());
            }
        }
        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $translator->trans($error->getMessage());
        }
        return $errors;
    }

    protected function validate(array $requiredParams = [])
    {
        foreach ($requiredParams as $requiredParam => $options) {
            if (!$this->hasRequestParameter($requiredParam) && $options['required']) {
                throw new HttpException(
                    Response::HTTP_BAD_REQUEST,
                    "Arguments expected: '" . implode("', '", array_keys($requiredParams)) . "'."
                );
            }
        }
    }
    //endregion random validate

    //region random entity, repository
    /**
     * @param BaseEntity $entity
     */
    protected function saveEntity($entity)
    {
        $this->managerRegistry->getManager()->persist($entity);
        $this->managerRegistry->getManager()->flush();
    }

    /**
     * @param  KernelInterface  $kernel Controlleryje pridėkit kaip parametrą, kad gautumėte jį.
     */
    protected function getApplication(KernelInterface $kernel): Application
    {
        if ($this->application === null) {
            $this->application = new Application($kernel);
            $this->application->setAutoExit(true);
        }
        return $this->application;
    }
    //endregion random entity, repository
}
