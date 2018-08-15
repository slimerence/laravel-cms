<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/7/18
 * Time: 9:41 PM
 */

namespace App\Models\Utils\Html\Form\Bulma;


use App\Models\Utils\Html\Interfaces\IInput;

class InputImpl implements IInput
{

    /**
     * 输出简单的文本类型 input 标签的方法, Bulma 主题
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
        $fieldValue = null,
        $placeholder = null,
        $label = null
    )
    {
        if(is_null($placeholder)){
            $placeholder = $this->_i18n($modelName,$fieldName.'_placeholder');
        }

        if(is_null($label)){
            $label = $this->_i18n($modelName,$fieldName);
        }
        if($isRequired){
            $placeholder = trans('general.Required').': '.$placeholder;
            $label = $label.' <span class="has-text-danger">*</span>';
        }else{
            $placeholder = trans('general.Optional').': '.$placeholder;
        }
        ?>
      <div class="field">
        <label class="label"><?php echo $label; ?></label>
        <div class="control"><input name="<?php echo $modelName.'['.$fieldName.']'; ?>" class="input" type="<?php echo $fieldName=='password'?'password':'text'; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $fieldValue; ?>"<?php echo $isRequired?' required':null ?>></div>
      </div>
        <?php
    }

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
        $fieldOptions = [],
        $defaultValue = null,
        $isRequired = true,
        $label = null
    )
    {
        if(is_null($label)){
            $label = $this->_i18n($modelName,$fieldName);
        }
        if($isRequired){
            $label = $label.' <span class="has-text-danger">*</span>';
        }
        $options = '';
        foreach ($fieldOptions as $value=>$text) {
            $options .= '<option value="'.$value.'" '.($defaultValue===$value?'selected':null).'>'.$text.'</option>';
        }
        ?>
        <div class="field">
          <label class="label"><?php echo $label; ?></label>
          <div class="control">
            <div class="select">
              <select name="<?php echo $modelName.'['.$fieldName.']'; ?>"<?php echo $isRequired?' required':null ?>><?php echo $options; ?></select>
            </div>
          </div>
        </div>
        <?php
    }

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
        $isRequired = true,
        $label = null,
        $defaultFilePath = null,
        $allowMultiple = false
    )
    {
        if($isRequired){
            $label = $label.' <span class="has-text-danger">*</span>';
        }
        $fileElementId = 'input_'.$fieldName;
        $fileNameElementId = $fileElementId.'_name';
        $onFileChangeCodes = 'if(this.files.length>0){document.getElementById(\''.$fileNameElementId.'\').innerHTML = this.files[0].name;};';
        if($allowMultiple){
            $onFileChangeCodes = 'if(this.files.length>0){document.getElementById(\''.$fileNameElementId.'\').innerHTML = this.files.length+\' files are selected.\';};';
        }
        ?>
        <div class="field">
          <label class="label"><?php echo $label; ?></label>
          <div class="control">
            <div class="file has-name">
              <label class="file-label">
                <input<?php echo $allowMultiple?' multiple':null ?> id="<?php echo $fileElementId; ?>" class="file-input" type="file" name="<?php echo $fieldName; ?><?php echo $allowMultiple?'[]':null; ?>"<?php echo $isRequired?' required':null ?> onchange="<?php echo $onFileChangeCodes; ?>">
                <span class="file-cta">
                            <span class="file-icon"><i class="fas fa-upload"></i></span>
                            <span class="file-label"><?php echo $allowMultiple? trans('general.Choose_files'):trans('general.Choose_files') ?></span>
                          </span>
                <span class="file-name" id="<?php echo $fileNameElementId; ?>"></span>
              </label>
                <?php
                if($defaultFilePath){
                    ?><p><a href="<?php echo asset($defaultFilePath) ?>" target="_blank">View my uploaded file</a></p><?php
                }
                ?>
            </div>
          </div>
        </div>
        <?php
    }
}