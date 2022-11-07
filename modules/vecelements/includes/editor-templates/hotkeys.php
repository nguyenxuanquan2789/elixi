<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or die;
?>
<script type="text/template" id="tmpl-elementor-hotkeys">
    <# var ctrlLabel = environment.mac ? 'Cmd' : 'Ctrl'; #>
    <div id="elementor-hotkeys__content">
        <div id="elementor-hotkeys__actions" class="elementor-hotkeys__col">

            <div class="elementor-hotkeys__header">
                <h3><?= __('Actions') ?></h3>
            </div>
            <div class="elementor-hotkeys__list">
                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Undo') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Z</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Redo') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>Z</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Copy') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>C</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Paste') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>V</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Paste Style') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>V</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Delete') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>Delete</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Duplicate') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>D</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Save') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>S</span>
                    </div>
                </div>

            </div>
        </div>

        <div id="elementor-hotkeys__navigation" class="elementor-hotkeys__col">

            <div class="elementor-hotkeys__header">
                <h3><?= __('Go To') ?></h3>
            </div>
            <div class="elementor-hotkeys__list">
                <!--div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Finder') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>E</span>
                    </div>
                </div-->

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Show / Hide Panel') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>P</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Responsive Mode') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>M</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('History') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>H</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Navigator') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>I</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Template Library') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>L</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Keyboard Shortcuts') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>?</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?= __('Quit') ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>Esc</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
