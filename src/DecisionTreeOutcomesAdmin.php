<?php

namespace DNADesign\SilverStripeElementalDecisionTree;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\SS_List;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use DNADesign\SilverStripeElementalDecisionTree\Model\DecisionTreeOutcome;

class DecisionTreeOutcomesAdmin extends ModelAdmin
{
    /**
     * Managed models.
     *
     * @var array
     */
    private static $managed_models = [
        DecisionTreeOutcome::class,
    ];

    /**
     * URL segment.
     *
     * @var string
     */
    private static $url_segment = 'outcomes';

    /**
     * Menu title.
     *
     * @var string
     */
    private static $menu_title = 'Outcomes';


    /**
     * Hide model admin from the CMS menu.
     *
     * @return bool
     */
    public function subsiteCMSShowInMenu()
    {
        if (Subsite::currentSubsite()->Title !== 'Build.it') {
            return false;
        }

        return true;
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = Form::create(
            $this,
            'EditForm',
            new FieldList($this->getGridField()),
            new FieldList()
        )->setHTMLID('Form_EditForm');
        $form->addExtraClass('cms-edit-form cms-panel-padded center flexbox-area-grow');
        $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
        $editFormAction = Controller::join_links($this->getLinkForModelTab($this->modelTab), 'EditForm');
        $form->setFormAction($editFormAction);
        $form->setAttribute('data-pjax-fragment', 'CurrentForm');

        return $form;
    }

}
