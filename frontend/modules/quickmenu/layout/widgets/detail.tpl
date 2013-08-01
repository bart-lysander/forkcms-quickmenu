{*
	variables that are available:
	- {$widgetQuickmenuCategories}:
*}

{option:widgetQuickmenuDetail}
	<section class="widgetQuickmenuDetail" class="mod">
		<div class="inner">
			<div class="bd content">
				<ul>
					{iteration:widgetQuickmenuDetail}
						<li{option:widgetQuickmenuDetail.selected} class="selected"{/option:widgetQuickmenuDetail.selected}>
							<a href="/{$widgetQuickmenuDetail.url}">
								{$widgetQuickmenuDetail.title}
							</a>
						</li>
					{/iteration:widgetQuickmenuDetail}
				</ul>
			</div>
		</div>
	</section>
{/option:widgetQuickmenuDetail}
