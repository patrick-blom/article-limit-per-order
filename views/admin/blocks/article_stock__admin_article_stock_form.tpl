[{$smarty.block.parent}]
<tr>
    <td class="edittext" colspan="2"><br>
        <fieldset title="[{oxmultilang ident="PB_GENERAL_ARTICLE_LIMIT_PER_ORDER_TEXT"}]" style="padding-left: 5px;">
            <legend>[{oxmultilang ident="PB_GENERAL_ARTICLE_LIMIT_PER_ORDER_TEXT"}]</legend><br>
            <table>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PB_ARTICLE_LIMIT_PER_ORDER_TEXT"}]
                    </td>
                    <td class="edittext">
                        <input type="text" class="editinput" size="40" maxlength="[{$edit->oxarticles__pbmaxorderlimit->fldmax_length}]" name="editval[oxarticles__pbmaxorderlimit]" value="[{$edit->oxarticles__pbmaxorderlimit->value}]" [{$readonly}]>
                        [{oxinputhelp ident="HELP_PB_ARTICLE_LIMIT_PER_ORDER_TEXT"}]
                    </td>
                </tr>
            </table>
        </fieldset>
    </td>
</tr>
