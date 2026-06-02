<?php
/**
 * Blog helpers, demo content, and rendering utilities.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current blog list page number.
 *
 * @return int
 */
function samabar_get_blog_paged() {
	$paged = get_query_var( 'paged' );
	if ( ! $paged ) {
		$paged = get_query_var( 'page' );
	}
	return max( 1, (int) $paged );
}

/**
 * Jalali month names.
 *
 * @return array<int, string>
 */
function samabar_get_jalali_month_names() {
	return array(
		1  => 'فروردین',
		2  => 'اردیبهشت',
		3  => 'خرداد',
		4  => 'تیر',
		5  => 'مرداد',
		6  => 'شهریور',
		7  => 'مهر',
		8  => 'آبان',
		9  => 'آذر',
		10 => 'دی',
		11 => 'بهمن',
		12 => 'اسفند',
	);
}

/**
 * Format post date in Jalali.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function samabar_format_post_date( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$ts      = get_post_time( 'U', true, $post_id );
	if ( ! $ts ) {
		return '';
	}

	$j      = samabar_gregorian_to_jalaali(
		(int) gmdate( 'Y', $ts ),
		(int) gmdate( 'n', $ts ),
		(int) gmdate( 'j', $ts )
	);
	$months = samabar_get_jalali_month_names();

	return sprintf(
		'%d %s %d',
		$j['jd'],
		$months[ $j['jm'] ] ?? '',
		$j['jy']
	);
}

/**
 * Estimate reading time label.
 *
 * @param string $content Post content.
 * @return string
 */
function samabar_estimate_reading_time( $content ) {
	$text  = wp_strip_all_tags( (string) $content );
	$words = preg_match_all( '/\S+/u', $text, $matches ) ? count( $matches[0] ) : 0;
	$mins  = max( 1, (int) ceil( $words / 180 ) );
	/* translators: %d: minutes */
	return sprintf( __( '%d دقیقه مطالعه', 'samabar' ), $mins );
}

/**
 * Primary category for a post.
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|null
 */
function samabar_get_primary_category( $post_id = null ) {
	$post_id    = $post_id ? (int) $post_id : get_the_ID();
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return null;
	}
	return $categories[0];
}

/**
 * Blog categories used for filters.
 *
 * @return array<int, WP_Term>
 */
function samabar_get_blog_filter_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Active blog category slug from query string.
 *
 * @return string
 */
function samabar_get_active_blog_category_slug() {
	if ( empty( $_GET['category'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}
	return sanitize_title( wp_unslash( $_GET['category'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Active blog search query.
 *
 * @return string
 */
function samabar_get_blog_search_query() {
	if ( empty( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}
	return sanitize_text_field( wp_unslash( $_GET['s'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Build WP_Query args for blog listing.
 *
 * @return array
 */
function samabar_get_blog_query_args() {
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => samabar_get_blog_paged(),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$category = samabar_get_active_blog_category_slug();
	if ( $category ) {
		$args['category_name'] = $category;
	}

	$search = samabar_get_blog_search_query();
	if ( $search ) {
		$args['s'] = $search;
	}

	return $args;
}

/**
 * Featured image URL or empty string.
 *
 * @param int|null $post_id Post ID.
 * @param string   $size    Image size.
 * @return string
 */
function samabar_get_post_thumbnail_url( $post_id = null, $size = 'large' ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$url     = get_the_post_thumbnail_url( $post_id, $size );
	return $url ? $url : '';
}

/**
 * Demo posts configuration.
 *
 * @return array<int, array<string, mixed>>
 */
function samabar_get_demo_blog_posts_config() {
	return array(
		array(
			'title'    => 'آینده لجستیک هوشمند در ایران',
			'slug'     => 'future-smart-logistics-iran',
			'category' => 'لجستیک هوشمند',
			'cat_slug' => 'logistics-smart',
			'author'   => 'تیم تحقیق و توسعه',
			'image'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUXSTFjtHwZ61zB6BQOCZ84mYiDMog6ozT1bDG_O24bNPuZxpDAB1-2DSC41QabGywDUAX2_dtSb1SANFNtd_cA5OvMZ2tPbaD7MKgUWTH6POmyUvCa61YL_Q42KmzcUMur7_xPU_pQEMA3Npngk_NU8YkeIAkWEHfQ7B8h_8kwsGMerG0v_9jo0LiqKy-rab69YlizwJP1ztbMA9HvcA2l4QxYNniRn2KpsVRHGRyFKGp6KkfesjP-RvZ9xrXMXKt8nrfJTb0YOs',
			'excerpt'  => 'چگونه فناوری‌های نوین مانند اینترنت اشیا و هوش مصنوعی در حال تغییر چهره صنعت حمل و نقل و لجستیک در کشور هستند.',
			'content'  => '<p>صنعت لجستیک ایران در نقطه عطفی قرار دارد. شرکت‌های B2B دیگر تنها به دنبال «ارزان‌ترین کرایه» نیستند؛ آن‌ها به دنبال شفافیت، پیش‌بینی‌پذیری و کنترل لحظه‌ای زنجیره تامین هستند.</p><h2>اینترنت اشیا در حمل‌ونقل</h2><p>سنسورهای GPS، دما و رطوبت روی ناوگان، امکان رهگیری دقیق محموله‌های حساس را فراهم می‌کنند. داده‌های این سنسورها مستقیماً در داشبورد مشتری نمایش داده می‌شود.</p><h2>هوش مصنوعی و مسیریابی</h2><p>الگوریتم‌های پیش‌بینی تقاضا به شرکت‌ها کمک می‌کنند ظرفیت ناوگان را بهینه تخصیص دهند و هزینه سوخت و زمان تحویل را کاهش دهند.</p><blockquote>لجستیک هوشمند فقط یک تکنولوژی نیست؛ تغییر مدل کسب‌وکار است.</blockquote><p>سما بار با ترکیب ثبت سفارش آنلاین، رهگیری لحظه‌ای و گزارش‌دهی شفاف، گام مهمی در این مسیر برداشته است.</p>',
			'date'     => '2023-10-07 10:00:00',
			'tags'     => array( 'لجستیک', 'هوش مصنوعی', 'IoT' ),
		),
		array(
			'title'    => 'بهینه‌سازی زنجیره تامین B2B',
			'slug'     => 'b2b-supply-chain-optimization',
			'category' => 'B2B',
			'cat_slug' => 'b2b',
			'author'   => 'واحد مشاوره سازمانی',
			'image'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBG6_SakxLI1LnLEDq882VC0qYun0-qDHYfEv4RNLWfzPZ2w1u9O36Ak_sjhlfbScrtl4qWSP1lhINx5mFWFxxT0NmawcipQU4SmnpNFuf4EBn5FvfHZgeXvL91Kl7FIR2CApirFmoIfg3DL7k29GMwOtV86Ov2kjm_uclicam2lFDIxhdsRYr2nH8Rc_IkiH47rmH3SxWRx7J0D44DncYu8LtfZ-sI3gHCk5b3ajbZn33Wzgq-4co8RVA43NJJpt6zWRQUxozK73Y',
			'excerpt'  => 'راهکارهای عملی برای کاهش هزینه‌ها و افزایش سرعت در زنجیره تامین کسب‌وکارهای بزرگ و متوسط.',
			'content'  => '<p>زنجیره تامین B2B شامل ده‌ها متغیر است: از انتخاب حامل تا زمان‌بندی بارگیری و بیمه محموله. کوچک‌ترین خطا می‌تواند هزینه‌های پنهان ایجاد کند.</p><h2>شفافیت هزینه</h2><p>قیمت‌گذاری یکپارچه و آنلاین، از بروز اختلاف در مرحله تحویل جلوگیری می‌کند. مشتری از ابتدا می‌داند چه مبلغی پرداخت می‌کند.</p><h2>یکپارچگی داده</h2><p>اتصال سفارش، رهگیری و گزارش مالی در یک پلتفرم، نیاز به تماس‌های مکرر با پشتیبانی را کاهش می‌دهد.</p><ul><li>کاهش زمان انتظار بارگیری</li><li>افزایش نرخ تحویل به‌موقع</li><li>بهبود رضایت مشتریان سازمانی</li></ul>',
			'date'     => '2023-10-04 09:30:00',
			'tags'     => array( 'B2B', 'زنجیره تامین', 'بهینه‌سازی' ),
		),
		array(
			'title'    => 'نرم‌افزارهای مدیریت ناوگان',
			'slug'     => 'fleet-management-software',
			'category' => 'فناوری',
			'cat_slug' => 'technology',
			'author'   => 'تیم فناوری',
			'image'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCNscYlOGWJpcdkZWxo1DxQ9q-cJe8t-msM_PTKCHe0L-Pp_C3sh4ogfhV5dxHMFwyYDrBilRL1dQpqxV9Sj5wMKhrsgCrscBT7hDjMGCxI23we_WIaDDIwFqNxzhTlygC2c3h-gvVAJQzPOLasuFVCK3PAXqFUdGhZTU_nF-6BWHeiYD7RhXPRn-y-52pvECfDcR7LaR6DFsBHKZyHkT8fh0BH2oo2v0e8RcGwBCJYy245vsKr8X_fYgE5nCAi1_pKUTYAkym0UUI',
			'excerpt'  => 'بررسی جامع بهترین نرم‌افزارهای مدیریت ناوگان حمل و نقل و تاثیر آنها بر بهره‌وری سازمانی.',
			'content'  => '<p>مدیریت ناوگان بدون داشبورد یکپارچه، مانند هدایت ترافیک بدون چراغ راهنمایی است. نرم‌افزارهای مدرن FMS امکان مشاهده موقعیت، مصرف سوخت و وضعیت سفارش را یکجا فراهم می‌کنند.</p><h2>ویژگی‌های کلیدی</h2><p>هشدارهای خودکار، گزارش KPI، و یکپارچگی با سیستم ERP از مهم‌ترین قابلیت‌ها هستند.</p><h2>انتخاب راهکار مناسب</h2><p>قبل از خرید، حجم سفارش روزانه، تنوع مسیر و نیاز به رهگیری مشتری را مشخص کنید. راهکار سما بار برای کسب‌وکارهایی طراحی شده که می‌خواهند بدون پیچیدگی فنی، لجستیک خود را دیجیتالی کنند.</p>',
			'date'     => '2023-09-26 11:15:00',
			'tags'     => array( 'ناوگان', 'FMS', 'فناوری' ),
		),
		array(
			'title'    => 'استانداردهای جدید بسته‌بندی',
			'slug'     => 'industrial-packaging-standards',
			'category' => 'لجستیک',
			'cat_slug' => 'logistics',
			'author'   => 'واحد عملیات',
			'image'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBjVchueETNI3MEgyXMfZywvO4VJ3N7EDB11tV7gW3w2xlCruyZ6ym7WJuuaIvbQC06ubfEKTkltQ8EG2vcpfxGkIuN37KcjjkPV1FqK34cajAdr9wTbxfI2N7bFsJ10zJEUyAX_hFIVABaYJ0DKLP89ekYJ8M1KBhUs-opOm3-zoj385DJRfPodSGKTYiVMgM1AwjLuJybfTiCRX0qEhZBedIdvNadDa4-HgTJYIcqIdx7dnt4p69CNz-XJOpyETfHr5_nRIzqPss',
			'excerpt'  => 'آشنایی با استانداردهای روز دنیا در بسته‌بندی محموله‌های صنعتی برای کاهش آسیب‌دیدگی.',
			'content'  => '<p>بسته‌بندی صحیح، اولین خط دفاعی در برابر آسیب محموله است. استانداردهای جدید بر پایداری، قابلیت انباشت و مقاومت در برابر رطوبت تاکید دارند.</p><h2>پالت‌بندی و مهار بار</h2><p>استفاده از تسمه، نایلون حرارتی و گوشه‌بر برای بارهای سنگین ضروری است. عدم رعایت این موارد علت اصلی خسارت در حمل بین‌شهری است.</p><h2>برچسب‌گذاری</h2><p>برچسب مبدا، مقصد، وزن و نوع بار باید خوانا و مقاوم در برابر آب باشد. این اطلاعات در مرکز عملیات سما بار به صورت دیجیتال نیز ثبت می‌شود.</p>',
			'date'     => '2023-09-19 08:45:00',
			'tags'     => array( 'بسته‌بندی', 'استاندارد', 'ایمنی بار' ),
		),
	);
}

/**
 * Seed demo blog categories and posts once.
 */
function samabar_seed_blog_content() {
	if ( get_option( 'samabar_blog_seeded' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	foreach ( samabar_get_demo_blog_posts_config() as $item ) {
		$term = term_exists( $item['cat_slug'], 'category' );
		if ( ! $term ) {
			$term = wp_insert_term(
				$item['category'],
				'category',
				array( 'slug' => $item['cat_slug'] )
			);
		}
		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;

		if ( get_page_by_path( $item['slug'], OBJECT, 'post' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $item['title'],
				'post_name'    => $item['slug'],
				'post_content' => $item['content'],
				'post_excerpt' => $item['excerpt'],
				'post_status'  => 'publish',
				'post_type'    => 'post',
				'post_author'  => 1,
				'post_date'    => $item['date'],
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		wp_set_post_categories( $post_id, array( $term_id ) );

		if ( ! empty( $item['tags'] ) ) {
			wp_set_post_tags( $post_id, $item['tags'], false );
		}

		if ( ! empty( $item['image'] ) ) {
			$attachment_id = media_sideload_image( $item['image'], $post_id, $item['title'], 'id' );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, (int) $attachment_id );
			}
		}
	}

	update_option( 'samabar_blog_seeded', 1, false );
}
add_action( 'init', 'samabar_seed_blog_content', 20 );

/**
 * Backfill tags on demo posts created before tag seeding.
 */
function samabar_sync_demo_blog_tags() {
	if ( get_option( 'samabar_blog_tags_synced' ) ) {
		return;
	}

	foreach ( samabar_get_demo_blog_posts_config() as $item ) {
		$post = get_page_by_path( $item['slug'], OBJECT, 'post' );
		if ( ! $post || empty( $item['tags'] ) ) {
			continue;
		}
		wp_set_post_tags( $post->ID, $item['tags'], false );
	}

	update_option( 'samabar_blog_tags_synced', 1, false );
}
add_action( 'init', 'samabar_sync_demo_blog_tags', 21 );

/**
 * Related posts for single view.
 *
 * @param int $post_id Post ID.
 * @param int $limit   Max posts.
 * @return array<int, WP_Post>
 */
function samabar_get_related_posts( $post_id, $limit = 3 ) {
	$post_id  = (int) $post_id;
	$limit    = max( 1, (int) $limit );
	$exclude  = array( $post_id );
	$related  = array();
	$categories = wp_get_post_categories( $post_id );

	if ( ! empty( $categories ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'post__not_in'   => $exclude,
				'category__in'   => $categories,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		$related = $query->posts;
	}

	if ( count( $related ) < $limit ) {
		$exclude = array_merge( $exclude, wp_list_pluck( $related, 'ID' ) );
		$query   = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $limit - count( $related ),
				'post__not_in'   => $exclude,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		$related = array_merge( $related, $query->posts );
	}

	return $related;
}

/**
 * Previous and next blog posts.
 *
 * @param int $post_id Post ID.
 * @return array{prev: WP_Post|null, next: WP_Post|null}
 */
function samabar_get_blog_adjacent_posts( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$prev    = get_previous_post( false, '', 'category' );
	$next    = get_next_post( false, '', 'category' );

	if ( ! $prev ) {
		$prev = get_previous_post();
	}
	if ( ! $next ) {
		$next = get_next_post();
	}

	return array(
		'prev' => ( $prev instanceof WP_Post ) ? $prev : null,
		'next' => ( $next instanceof WP_Post ) ? $next : null,
	);
}

/**
 * Author bio text for blog posts.
 *
 * @param int|null $author_id Author user ID.
 * @return string
 */
function samabar_get_blog_author_bio( $author_id = null ) {
	$author_id = $author_id ? (int) $author_id : (int) get_the_author_meta( 'ID' );
	$bio       = get_the_author_meta( 'description', $author_id );

	if ( $bio ) {
		return (string) $bio;
	}

	return __( 'تیم محتوای تخصصی سما بار در حوزه لجستیک و حمل‌ونقل B2B', 'samabar' );
}

/**
 * Pagination link base for blog page.
 *
 * @return string
 */
function samabar_get_blog_pagination_base() {
	$blog_url = trailingslashit( samabar_get_blog_url() );
	if ( get_option( 'permalink_structure' ) ) {
		return user_trailingslashit( $blog_url . 'page/%#%/' );
	}
	return add_query_arg( 'page', '%#%', $blog_url );
}

/**
 * Filter URL for blog listing.
 *
 * @param array $args Query args.
 * @return string
 */
function samabar_get_blog_filter_url( $args = array() ) {
	$url = samabar_get_blog_url();
	return add_query_arg( $args, $url );
}
