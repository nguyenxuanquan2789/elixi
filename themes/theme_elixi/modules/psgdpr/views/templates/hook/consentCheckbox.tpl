{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="checkbox">
    <div class="checker" id="uniform-psgdpr">
        <span><input type="checkbox" name="psgdpr-consent" id="psgdpr-consent" value="1" required></span>
    </div>
    <label for="psgdpr-consent">{$label nofilter}{* html data *}</label>
</div>

{literal}
<script>
    var currentPage = "{/literal}{$currentPage|escape:'htmlall':'UTF-8'}{literal}";
    if (currentPage == 'identity') {
        var psgdpr_front_controller = "{/literal}{$psgdpr_front_controller|escape:'htmlall':'UTF-8'}{literal}";
        var psgdpr_id_customer = "{/literal}{$psgdpr_id_customer|escape:'htmlall':'UTF-8'}{literal}";
        var psgdpr_customer_token = "{/literal}{$psgdpr_customer_token|escape:'htmlall':'UTF-8'}{literal}";
        var psgdpr_id_guest = "{/literal}{$psgdpr_id_guest|escape:'htmlall':'UTF-8'}{literal}";
        var psgdpr_guest_token = "{/literal}{$psgdpr_guest_token|escape:'htmlall':'UTF-8'}{literal}";

        psgdpr_front_controller = psgdpr_front_controller.replace(/\amp;/g,'');

        parentForm = $('#psgdpr-consent').closest('form');

        $(document).on('submit', parentForm, function(event) {
            $.ajax({
                data: 'POST',
                url: psgdpr_front_controller,
                data: {
                    ajax: true,
                    action: 'AddLog',
                    id_customer: psgdpr_id_customer,
                    customer_token: psgdpr_customer_token,
                    id_guest: psgdpr_id_guest,
                    guest_token: psgdpr_guest_token,
                },
                success: function (data) {
                    // parentForm.submit();
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        });
    }
</script>
{/literal}