[{$smarty.block.parent}]
<tr>
    <td class="edittext">
        [{oxmultilang ident="PB_ORDER_ARTICLE_LIMIT"}]
    </td>
    <td class="edittext">
        <input type="text" class="editinput" size="35" name="editval[oxarticles__pbmaxorderlimit]"
               value="[{$edit->oxarticles__pbmaxorderlimit->value}]" [{$readonly}]>
        [{oxinputhelp ident="HELP_PB_ORDER_ARTICLE_LIMIT"}]
    </td>
</tr>
