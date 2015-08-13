<article id="db_article">
	<nav id="db_nav">
		<? if ($model->page > 1) {
			$this->renderLink("/database/".$model->itemtype, "<<");
			$this->renderLink("/database/".$model->itemtype."/".($model->page-1), "<");
		}
		$pagespan = $model->getPageSpan();
		for ($i = $pagespan[0]; $i <= $pagespan[1]; $i++) {
			if ($i == $model->page) { ?>
			<a class="currentpage"><?=$i?></a>
			<? } else {
				$this->renderLink("/database/".$model->itemtype."/$i", $i);
			}
		}
		if ($model->page < $model->maxpage) {
			$this->renderLink("/database/".$model->itemtype."/".($model->page+1), ">");
			$this->renderLink("/database/".$model->itemtype."/".$model->maxpage, ">>");
		} ?>
	</nav>
	<table width="50%">
		<tr>
			<th width="55%">Name</th>
			<th width="20%">Type</th>
			<th width="20%">Minimum Level</th>
			<th width="5%">Grade</th>

		<?php foreach($model->list as $item) { ?>

		<tr>
			<td class="color<?=$item->color?> item_link"><?php $this->renderLink("/database/item/".$item->id, $item->name) ?></td>
			<td><?=$item->subType?></td>
			<td><?=$item->min_level?></td>
			<td><?=$item->grade?></td>
		</tr>

		<? } ?>
	</table>
</article>