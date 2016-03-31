<?php
/**
 *  @package DocImport
 *  @copyright Copyright (c)2010-2016 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/** @var \Akeeba\DocImport\Site\View\Search\Html $this */

// Get the submission URL
$returnUrl = base64_encode(JUri::current());
$submitUrl = JRoute::_('index.php?option=com_docimport&view=Search');

JHtml::_('formbehavior.chosen', 'select.fancySelect')
?>

<form action="<?php echo $submitUrl ?>" method="POST" id="dius-form">
	<div id="dius-searchform" class="row col-xs-12">
		<div class="input-group">
			<input type="text" class="form-control" id="dius-search" name="search"
				   placeholder="<?php echo JText::_('COM_DOCIMPORT_SEARCH_LBL_SEARCHSUPPORT') ?>"
				   value="<?php echo htmlentities($this->search); ?>"
			>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit">
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</span>
		</div>
	</div>
	<div id="dius-searchutils">
		<div id="dius-searching-container" class="row col-xs-12">
			<label id="dius-searching-label" for="dius-searching-areas">
				<?php echo JText::_('COM_DOCIMPORT_SEARCH_LBL_SEARCHINGSECTIONS') ?>
			</label>
			<span id="dius-searching-areas"></span>
			<span id="dius-searching-toggle" class="pull-right">
				<a data-toggle="collapse" href="#dius-searchutils-collapsible" aria-expanded="false" aria-controls="dius-searchutils-collapsible">
					<?php echo JText::_('COM_DOCIMPORT_SEARCH_LBL_SEARCHTOOLS'); ?>
				</a>
			</span>
		</div>
		<div id="dius-searchutils-collapsible">
			<div class="row col-xs-12 form">

				<div class="panel-group" id="dius-searchutils-groupcontainer">

					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label for="dius-searchutils-areas">
									<?php echo JText::_('COM_DOCIMPORT_SEARCH_LBL_SECTIONS'); ?>
								</label>
								<?php echo JHtml::_('select.genericlist', $this->areaOptions, 'areas', [
									'multiple' => 'multiple',
									'class' => 'fancySelect form-control',
									'onchange' => 'akeeba.DocImport.Search.sectionsChange(this)'
								], 'value', 'text', $this->areas, 'dius-searchutils-areas') ?>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>

	<input type="hidden" name="<?php echo JSession::getFormToken() ?>" value="1" />
</form>

<?php if (empty($this->search)) return; ?>

<?php
// TODO Read default active tab from component configuration
$tabs   = array_keys($this->items);
$active = $tabs[1];
?>
<div class="row col-xs-12 form">
	<div class="panel-group" id="dius-results-accordion" role="tablist" aria-multiselectable="true">
	<?php foreach ($this->items as $section => $data):
		$ariaExpanded = ($section == $active) ? 'true' : 'false';
		$collapseClass = ($section == $active) ? 'collapse in' : 'collapse';
	?>

		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="dius-results-slide-<?php echo $section ?>-head">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#dius-results-accordion"
					   href="#dius-results-slide-<?php echo $section ?>"
					   aria-expanded="<?php echo $ariaExpanded ?>"
					   aria-controls="dius-results-slide-<?php echo $section ?>"
					>
						<?php echo JText::_('COM_DOCIMPORT_SEARCH_SECTION_' . $section) ?>
					</a>
				</h4>
			</div>
			<div id="dius-results-slide-<?php echo $section ?>"
				 class="panel-collapse <?php echo $collapseClass ?>"
				 role="tabpanel"
				 aria-labelledby="dius-results-slide-<?php echo $section ?>-head"
			>
				<div class="panel-body">
					<?php
					// Render the section using template sectionName, e.g. joomla
					try
					{
						echo $this->loadAnyTemplate('site:com_docimport/Search/' . $section, $data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage(); die;
					}
					?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
</div>

<div class="row col-xs-12 form">
	<div class="pagination">
		<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</div>