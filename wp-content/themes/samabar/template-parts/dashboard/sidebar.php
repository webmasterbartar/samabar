<?php
/**
 * Dashboard sidebar navigation.
 *
 * @package Samabar
 */

$panel_url    = samabar_get_dashboard_url();
$tracking_url = samabar_get_tracking_url();
$order_url    = samabar_get_order_url();
$pricing_url  = samabar_get_pricing_url();
$faq_url      = samabar_get_faq_url();
$home_url     = home_url( '/' );
?>
<aside class="dashboard-sidebar" id="dashboard-sidebar" aria-label="<?php esc_attr_e( 'منوی داشبورد', 'samabar' ); ?>">
	<div class="dashboard-sidebar__brand">
		<a class="dashboard-sidebar__logo" href="<?php echo esc_url( $home_url ); ?>">سما بار</a>
		<span class="dashboard-sidebar__tagline">مدیریت لجستیک هوشمند</span>
	</div>

	<nav class="dashboard-sidebar__nav">
		<a class="dashboard-sidebar__link dashboard-sidebar__link--active" href="<?php echo esc_url( $panel_url ); ?>">
			<span class="material-symbols-outlined icon" aria-hidden="true">dashboard</span>
			<span>داشبورد</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $panel_url ); ?>#active-orders">
			<span class="material-symbols-outlined icon" aria-hidden="true">local_shipping</span>
			<span>سفارش‌های فعال</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $panel_url ); ?>#history-orders">
			<span class="material-symbols-outlined icon" aria-hidden="true">history</span>
			<span>تاریخچه</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $panel_url ); ?>#payments">
			<span class="material-symbols-outlined icon" aria-hidden="true">payments</span>
			<span>پرداخت‌ها</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $panel_url ); ?>#profile">
			<span class="material-symbols-outlined icon" aria-hidden="true">person</span>
			<span>پروفایل</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $tracking_url ); ?>">
			<span class="material-symbols-outlined icon" aria-hidden="true">location_on</span>
			<span>رهگیری</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
			<span class="material-symbols-outlined icon" aria-hidden="true">support_agent</span>
			<span>پشتیبانی</span>
		</a>
	</nav>

	<div class="dashboard-sidebar__foot">
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $home_url ); ?>">
			<span class="material-symbols-outlined icon" aria-hidden="true">home</span>
			<span>صفحه اصلی</span>
		</a>
		<a class="dashboard-sidebar__link" href="<?php echo esc_url( $faq_url ); ?>">
			<span class="material-symbols-outlined icon" aria-hidden="true">help</span>
			<span>راهنما</span>
		</a>
		<button type="button" class="dashboard-sidebar__link dashboard-sidebar__logout" id="dashboard-logout">
			<span class="material-symbols-outlined icon" aria-hidden="true">logout</span>
			<span>خروج</span>
		</button>
	</div>
</aside>
<div class="dashboard-overlay" id="dashboard-overlay" hidden></div>
