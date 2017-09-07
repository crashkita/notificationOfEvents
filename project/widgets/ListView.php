<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\widgets\ListView as BaseListView;
/**
 * Class ListView
 * @package app\widgets
 */
class ListView extends BaseListView
{
    /**
     * @var array the configuration for the sizer widget. By default, [[LinkSizer]] will be
     * used to render the sizer. You can use a different widget class by configuring the "class" element.
     * Note that the widget must support the `pagination` property which will be populated with the
     * [[\yii\data\BaseDataProvider::pagination|pagination]] value of the [[dataProvider]].
     */
    public $sizer = [];

    /**
     * @param string $name
     * @return bool|string
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{sizer}':
                return $this->renderSizer();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * @return string
     */
    public function renderSizer()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkSizer */
        $sizer = $this->sizer;
        $class = ArrayHelper::remove($sizer, 'class', LinkSizer::class);
        $sizer['pagination'] = $pagination;
        $sizer['view'] = $this->getView();

        return $class::widget($sizer);
    }
}