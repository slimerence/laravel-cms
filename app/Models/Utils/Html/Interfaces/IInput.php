<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/7/18
 * Time: 9:37 PM
 */

namespace App\Models\Utils\Html\Interfaces;


interface IInput
{
    /**
     * 输出简单的文本类型 input 标签的方法
     * @param $modelName
     * @param $fieldName
     * @param bool $isRequired
     * @param null $fieldValue
     * @param null $placeholder
     * @param null $label
     * @return mixed
     */
    public function simpleTextField(
        $modelName,
        $fieldName,
        $isRequired = true,
        $fieldValue=null,
        $placeholder=null,
        $label=null
    );

    /**
     * 输出简单的文本类型 select 标签的方法
     * @param $modelName
     * @param $fieldName
     * @param array $fieldOptions
     * @param null $defaultValue
     * @param bool $isRequired
     * @param null $label
     * @return mixed
     */
    public function simpleSelectField(
        $modelName,
        $fieldName,
        $fieldOptions=[],
        $defaultValue=null,
        $isRequired=true,
        $label=null
    );

    /**
     * 输出简单的文本类型 file 标签的方法
     * @param $fieldName
     * @param bool $isRequired
     * @param null $label
     * @param null $defaultFilePath
     * @param bool $allowMultiple
     * @return mixed
     */
    public function simpleFileField(
        $fieldName,
        $isRequired=true,
        $label=null,
        $defaultFilePath=null,
        $allowMultiple=false
    );
}