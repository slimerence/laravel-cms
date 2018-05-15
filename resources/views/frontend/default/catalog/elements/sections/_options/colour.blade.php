@if(count($product_colours)>0)
    <div class="hidden-first">
        <div class="float-left mr-4 mt-1" style="{{ $agentObject->isPhone() ? 'width: 12%;' : 'width: 20%;' }}">Color:</div>
        <el-select style="float: left;" v-model="selectedColour" placeholder="Please Choose Colour ...">
            <el-option v-for="(item, colorIndex) in productColours"
                    :key="item.name"
                    :label="item.name+(item.extra_money>0 ? ' + $'+item.extra_money : '')"
                    :value="item"
            >
                <div class="color-text-box" v-if="item.type=={{ \App\Models\Utils\ColourTool::$TYPE_TEXT }}">
                    <span style="float: left">@{{ item.name }} @{{ item.extra_money>0 ? ' + $'+item.extra_money : null }}</span>
                </div>
                <div class="color-hex-box" v-if="item.type=={{ \App\Models\Utils\ColourTool::$TYPE_HEX_CODE}}">
                    <span style="float: left">@{{ item.name }} @{{ item.extra_money>0 ? ' + $'+item.extra_money : null }}</span>
                    <div :style="{background:item.value,width:'50px',height:'30px',float:'right',marginBottom:'10px'}"></div>
                </div>
                <div class="color-image-box" v-if="item.type=={{ \App\Models\Utils\ColourTool::$TYPE_IMAGE}}">
                    <span style="float: left">@{{ item.name }}  @{{ item.extra_money>0 ? ' + $'+item.extra_money : null }}</span>
                    <img :src="item.imageUrl" style="width:33px;height:33px;float:right;margin-bottom:10px;" />
                </div>
            </el-option>
        </el-select>
        <div class="selected-color-display" v-if="selectedColour" style="{{ $agentObject->isPhone() ? 'float:left;margin-left:0px;' : null }}">
            <div class="color-hex-box" v-if="selectedColour.type=={{ \App\Models\Utils\ColourTool::$TYPE_HEX_CODE}}">
                <div class="thumb" :style="{background:selectedColour.value}"></div>
            </div>
            <div class="color-image-box" v-if="selectedColour.type=={{ \App\Models\Utils\ColourTool::$TYPE_IMAGE }}">
                <img class="thumb" :src="selectedColour.imageUrl"  style="{{ $agentObject->isPhone() ? 'margin-top:20px;width:190px;height:auto;' : null }}" />
            </div>
        </div>
    </div>
    <div class="is-clearfix mb-20"></div>
@endif
