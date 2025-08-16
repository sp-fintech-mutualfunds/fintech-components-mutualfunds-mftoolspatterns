<?php

namespace Apps\Fintech\Components\Mf\Tools\Patterns;

use Apps\Fintech\Packages\Adminltetags\Traits\DynamicTable;
use Apps\Fintech\Packages\Mf\Amcs\MfAmcs;
use Apps\Fintech\Packages\Mf\Schemes\MfSchemes;
use Apps\Fintech\Packages\Mf\Tools\Patterns\MfToolsPatterns;
use System\Base\BaseComponent;

class PatternsComponent extends BaseComponent
{
    use DynamicTable;

    protected $mfToolsPatternsPackage;

    protected $mfAmcsPackage;

    protected $schemesPackage;

    public function initialize()
    {
        $this->mfToolsPatternsPackage = $this->usePackage(MfToolsPatterns::class);

        $this->mfAmcsPackage = $this->usePackage(MfAmcs::class);

        $this->schemesPackage = $this->usePackage(MfSchemes::class);
    }

    /**
     * @acl(name=view)
     */
    public function viewAction()
    {
        if (isset($this->getData()['id'])) {
            $this->view->amcs = $this->mfAmcsPackage->getAll()->mfamcs;

            if ($this->getData()['id'] != 0) {
                $pattern = $this->mfToolsPatternsPackage->getById((int) $this->getData()['id']);

                if (!$pattern) {
                    return $this->throwIdNotFound();
                }

                $this->view->pattern = $pattern;
            }

            $this->view->pick('patterns/view');

            return;
        }

        $controlActions =
            [
                // 'disableActionsForIds'  => [1],
                'actionsToEnable'       =>
                [
                    'edit'      => 'mf/tools/patterns',
                    'remove'    => 'mf/tools/patterns/remove'
                ]
            ];

        $this->generateDTContent(
            $this->mfToolsPatternsPackage,
            'mf/tools/patterns/view',
            null,
            ['name'],
            true,
            ['name'],
            $controlActions,
            null,
            null,
            'name'
        );

        $this->view->pick('patterns/list');
    }

    /**
     * @acl(name=add)
     */
    public function addAction()
    {
        $this->requestIsPost();

        $this->mfToolsPatternsPackage->addMfToolsPatterns($this->postData());

        $this->addResponse(
            $this->mfToolsPatternsPackage->packagesData->responseMessage,
            $this->mfToolsPatternsPackage->packagesData->responseCode,
            $this->mfToolsPatternsPackage->packagesData->responseData?? []
        );
    }

    /**
     * @acl(name=update)
     */
    public function updateAction()
    {
        $this->requestIsPost();

        $this->mfToolsPatternsPackage->updateMfToolsPatterns($this->postData());

        $this->addResponse(
            $this->mfToolsPatternsPackage->packagesData->responseMessage,
            $this->mfToolsPatternsPackage->packagesData->responseCode,
            $this->mfToolsPatternsPackage->packagesData->responseData?? []
        );
    }

    /**
     * @acl(name=remove)
     */
    public function removeAction()
    {
        $this->requestIsPost();

        $this->mfToolsPatternsPackage->removeMfToolsPatterns($this->postData()['id']);

        $this->addResponse(
            $this->mfToolsPatternsPackage->packagesData->responseMessage,
            $this->mfToolsPatternsPackage->packagesData->responseCode,
            $this->mfToolsPatternsPackage->packagesData->responseData?? []
        );
    }

    public function generatePatternAction()
    {
        $this->requestIsPost();

        $this->mfToolsPatternsPackage->generatePattern($this->postData());

        $this->addResponse(
            $this->mfToolsPatternsPackage->packagesData->responseMessage,
            $this->mfToolsPatternsPackage->packagesData->responseCode,
            $this->mfToolsPatternsPackage->packagesData->responseData?? []
        );
    }
}