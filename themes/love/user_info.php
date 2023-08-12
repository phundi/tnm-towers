<html>
	<head>
		<title><?php echo __( 'My Information' ); ?></title>
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8">

<style>
* {
    box-sizing: border-box;
}
a {
	text-decoration: none;
}
body {
	background: #c649b8;margin: 0;
	font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
header {
	text-align: center;
    margin: 70px 0 50px;
}
header img {
	width: 100%;
    max-width: 240px;
}
h2 {
	font-weight: 400;
    display: flex;
    align-items: center;text-transform: capitalize;
}
h2 svg {
color: #2196F3;
    vertical-align: middle;
    margin-right: 12px;
}
.container {
	width: 100%;
    max-width: 1090px;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    border-radius: 7px;
}
.mt0 {
    margin-top: 0;
}
.mb0 {
    margin-bottom: 0;
}
table {
	margin: auto;
    margin-top: 40px;
	margin-bottom: 70px;
    width: 100%;
    max-width: 600px;
}
table tr td {
	width: 50%;
    padding: 5px 10px;
    border-bottom: 1px solid #ececec;
}
table tr td:first-child {
	font-weight: 500;
}
.users_list {
	overflow: hidden;
}
.users_list .profile-style {
	width: 20%;
    float: left;
    box-shadow: 0 0 0 1px #dedede;
    padding: 15px;text-align: center;
}
.users_list .profile-style .avatar {
	width: 120px;
    height: 120px;
    margin: 0 auto 13px;
}
.users_list .profile-style .avatar img {
	border-radius: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.users_list .profile-style > span > a {
	    color: #020202;
    text-decoration: none;
    font-size: 18px;
}
.users_list .profile-style .page-website {
	color: #7d7d7d;
    font-size: 13px;
    margin-top: 3px;
}
footer {
	border-top: 1px solid #ddd;
    margin-top: 40px;
    text-align: center;
    padding-top: 12px;color: #777777;
}
</style>
	</head>
	<body>
		<header>
			<a class="brand header-brand" href="<?php echo $site_url; ?>">
				<img width="130" src="<?php echo $theme_url.'assets/img/logo.png';?>" alt="Logo">
			</a>
		</header>
		<div class="container">
			<?php if (!empty($user_info['setting'])) { ?>
				<style>
					.cover {margin: 0 -20px;height: 363px;}
					.cover img {width: 100%;height: 363px;object-fit: cover;}
					.main_avatar {text-align: center;position: relative;margin-top: -65px;}
					.main_avatar img {border-radius: 50%;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);padding: 3px;background-color: white;width: 150px;height: 150px;object-fit: cover;}
					.about {text-align: center;}
					.name {text-align: center;font-weight: 500;margin-bottom: 0;font-size: 22px;}
					.username {text-align: center;margin-top: 0;}
				</style>
				<h2 class="mt0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,3H3C2,3 1,4 1,5V19A2,2 0 0,0 3,21H21C22,21 23,20 23,19V5C23,4 22,3 21,3M5,17L8.5,12.5L11,15.5L14.5,11L19,17H5Z" /></svg> <?php echo __( 'Avatar' ); ?> & <?php echo __( 'Cover' ); ?></h2>
				<?php if (!empty($user_info['setting']->cover)) { ?>
					<div class="cover"><img src="<?php echo($user_info['setting']->cover) ?>"></div>
				<?php } ?>
				<?php if (!empty($user_info['setting']->avater)) { ?>
					<div class="main_avatar"><img src="<?php echo($user_info['setting']->avater->avater) ?>"></div>
				<?php } ?>
				<?php if (!empty($user_info['setting']->full_name)) { ?>
				<h3 class="name">
						<?php echo $user_info['setting']->full_name.$user_info['setting']->pro_icon; ?>
				</h3>
				<?php } ?>
				<?php if (!empty($user_info['setting']->username)) { ?>
					<p class="username">@<?php echo($user_info['setting']->username) ?></p>
				<?php } ?>
				<?php if (!empty($user_info['setting']->about)) { ?>
					<p class="about"><?php echo br2nl($user_info['setting']->about); ?></p>
				<?php } ?>
				<table>
					<?php if (!empty($user_info['setting']->email)) { ?>
						<tr>
							<td><?php echo __( 'Email' ); ?></td>
							<td><?php echo($user_info['setting']->email) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->country) && !empty(Dataset::load('countries')[$user_info['setting']->country]['name'])) { ?>
						<tr>
							<td><?php echo __( 'Country' ); ?></td>
							<td><?php echo(Dataset::load('countries')[$user_info['setting']->country]['name']) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->phone_number)) { ?>
						<tr>
							<td><?php echo __( 'Mobile Number' ); ?></td>
							<td><?php echo($user_info['setting']->phone_number) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->gender) && !empty(Dataset::load('gender')[$user_info['setting']->gender])) { ?>
						<tr>
							<td><?php echo __( 'Gender' ); ?></td>
							<td><?php echo(Dataset::load('gender')[$user_info['setting']->gender]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->birthday)) { ?>
						<tr>
							<td><?php echo __( 'Birth date' ); ?></td>
							<td><?php echo($user_info['setting']->birthday) ?></td>
						</tr>
					<?php } ?>
						<tr>
							<td><?php echo __( 'Member Type' ); ?></td>
							<td><?php echo($user_info['setting']->is_pro == 1 ? __( 'Pro Member' ) : __( 'Free Member' )) ?></td>
						</tr>
					<?php if (!empty($user_info['setting']->location)) { ?>
						<tr>
							<td><?php echo __( 'Location' ); ?></td>
							<td><?php echo($user_info['setting']->location) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->interest)) { ?>
						<tr>
							<td><?php echo __( 'Interest' ); ?></td>
							<td><?php echo($user_info['setting']->interest) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->relationship) && !empty(Dataset::load('relationship')[$user_info['setting']->relationship])) { ?>
						<tr>
							<td><?php echo __( 'Relationship status' ); ?></td>
							<td><?php echo(Dataset::load('relationship')[$user_info['setting']->relationship]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->language) && !empty(Dataset::load('language')[$user_info['setting']->language])) { ?>
						<tr>
							<td><?php echo __( 'Preferred Language' ); ?></td>
							<td><?php echo(Dataset::load('language')[$user_info['setting']->language]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->work_status) && !empty(Dataset::load('work_status')[$user_info['setting']->work_status])) { ?>
						<tr>
							<td><?php echo __( 'Work status' ); ?></td>
							<td><?php echo(Dataset::load('work_status')[$user_info['setting']->work_status]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->education) && !empty(Dataset::load('education')[$user_info['setting']->education])) { ?>
						<tr>
							<td><?php echo __( 'Education Level' ); ?></td>
							<td><?php echo(Dataset::load('education')[$user_info['setting']->education]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->ethnicity) && !empty(Dataset::load('ethnicity')[$user_info['setting']->ethnicity])) { ?>
						<tr>
							<td><?php echo __( 'Ethnicity' ); ?></td>
							<td><?php echo(Dataset::load('ethnicity')[$user_info['setting']->ethnicity]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->body) && !empty(Dataset::load('body')[$user_info['setting']->body])) { ?>
						<tr>
							<td><?php echo __( 'Body Type' ); ?></td>
							<td><?php echo(Dataset::load('body')[$user_info['setting']->body]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->height) && !empty(Dataset::load('height')[$user_info['setting']->height])) { ?>
						<tr>
							<td><?php echo __( 'Height' ); ?></td>
							<td><?php echo(Dataset::load('height')[$user_info['setting']->height]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->hair_color) && !empty(Dataset::load('hair_color')[$user_info['setting']->hair_color])) { ?>
						<tr>
							<td><?php echo __( 'Hair Color' ); ?></td>
							<td><?php echo(Dataset::load('hair_color')[$user_info['setting']->hair_color]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->character) && !empty(Dataset::load('character')[$user_info['setting']->character])) { ?>
						<tr>
							<td><?php echo __( 'Character' ); ?></td>
							<td><?php echo(Dataset::load('character')[$user_info['setting']->character]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->children) && !empty(Dataset::load('children')[$user_info['setting']->children])) { ?>
						<tr>
							<td><?php echo __( 'Children' ); ?></td>
							<td><?php echo(Dataset::load('children')[$user_info['setting']->children]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->friends) && !empty(Dataset::load('friends')[$user_info['setting']->friends])) { ?>
						<tr>
							<td><?php echo __( 'Friends' ); ?></td>
							<td><?php echo(Dataset::load('friends')[$user_info['setting']->friends]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->pets) && !empty(Dataset::load('pets')[$user_info['setting']->pets])) { ?>
						<tr>
							<td><?php echo __( 'Pets' ); ?></td>
							<td><?php echo(Dataset::load('pets')[$user_info['setting']->pets]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->live_with) && !empty(Dataset::load('live_with')[$user_info['setting']->live_with])) { ?>
						<tr>
							<td><?php echo __( 'I live with' ); ?></td>
							<td><?php echo(Dataset::load('live_with')[$user_info['setting']->live_with]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->car) && !empty(Dataset::load('car')[$user_info['setting']->car])) { ?>
						<tr>
							<td><?php echo __( 'Car' ); ?></td>
							<td><?php echo(Dataset::load('car')[$user_info['setting']->car]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->religion) && !empty(Dataset::load('religion')[$user_info['setting']->religion])) { ?>
						<tr>
							<td><?php echo __( 'Religion' ); ?></td>
							<td><?php echo(Dataset::load('religion')[$user_info['setting']->religion]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->smoke) && !empty(Dataset::load('smoke')[$user_info['setting']->smoke])) { ?>
						<tr>
							<td><?php echo __( 'Smoke' ); ?></td>
							<td><?php echo(Dataset::load('smoke')[$user_info['setting']->smoke]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->drink) && !empty(Dataset::load('drink')[$user_info['setting']->drink])) { ?>
						<tr>
							<td><?php echo __( 'Drink' ); ?></td>
							<td><?php echo(Dataset::load('drink')[$user_info['setting']->drink]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->travel) && !empty(Dataset::load('travel')[$user_info['setting']->travel])) { ?>
						<tr>
							<td><?php echo __( 'Travel' ); ?></td>
							<td><?php echo(Dataset::load('travel')[$user_info['setting']->travel]) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->music)) { ?>
						<tr>
							<td><?php echo __( 'Music Genre' ); ?></td>
							<td><?php echo($user_info['setting']->music) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->dish)) { ?>
						<tr>
							<td><?php echo __( 'Dish' ); ?></td>
							<td><?php echo($user_info['setting']->dish) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->song)) { ?>
						<tr>
							<td><?php echo __( 'Song' ); ?></td>
							<td><?php echo($user_info['setting']->song) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->hobby)) { ?>
						<tr>
							<td><?php echo __( 'Hobby' ); ?></td>
							<td><?php echo($user_info['setting']->hobby) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->city)) { ?>
						<tr>
							<td><?php echo __( 'City' ); ?></td>
							<td><?php echo($user_info['setting']->city) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->sport)) { ?>
						<tr>
							<td><?php echo __( 'Sport' ); ?></td>
							<td><?php echo($user_info['setting']->sport) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->book)) { ?>
						<tr>
							<td><?php echo __( 'Book' ); ?></td>
							<td><?php echo($user_info['setting']->book) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->movie)) { ?>
						<tr>
							<td><?php echo __( 'Movie' ); ?></td>
							<td><?php echo($user_info['setting']->movie) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->colour)) { ?>
						<tr>
							<td><?php echo __( 'Color' ); ?></td>
							<td><?php echo($user_info['setting']->colour) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->tv)) { ?>
						<tr>
							<td><?php echo __( 'TV Show' ); ?></td>
							<td><?php echo($user_info['setting']->tv) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->facebook)) { ?>
						<tr>
							<td><?php echo __( 'Facebook' ); ?></td>
							<td><?php echo($user_info['setting']->facebook) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->twitter)) { ?>
						<tr>
							<td><?php echo __( 'Twitter' ); ?></td>
							<td><?php echo($user_info['setting']->twitter) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->google)) { ?>
						<tr>
							<td><?php echo __( 'VK' ); ?></td>
							<td><?php echo($user_info['setting']->google) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->instagram)) { ?>
						<tr>
							<td><?php echo __( 'instagram' ); ?></td>
							<td><?php echo($user_info['setting']->instagram) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->linkedin)) { ?>
						<tr>
							<td><?php echo __( 'LinkedIn' ); ?></td>
							<td><?php echo($user_info['setting']->linkedin) ?></td>
						</tr>
					<?php } ?>
					<?php if (!empty($user_info['setting']->website)) { ?>
						<tr>
							<td><?php echo __( 'Website' ); ?></td>
							<td><?php echo($user_info['setting']->website) ?></td>
						</tr>
					<?php } ?>
				</table>
					<?php if (!empty($user_info['setting']->session)) { ?>
					<style>
						.active_sessions {padding: 0 15px;margin-bottom: 10px;}
						.active_sessions .as_list {padding: 13px 10px;position: relative;border-bottom: 1px solid rgba(0, 0, 0, 0.07);}
						.active_sessions .as_list:last-child {border: 0;}
						.active_sessions .as_list .platform_icon {margin-right: 15px;float: left;width: 36px;height: 36px;display: flex;align-items: center;justify-content: center;}
						.active_sessions .as_list .platform_icon svg {width: 28px;height: 28px;}
						.active_sessions .as_list .log_out_session {float: right;width: 35px;height: 35px;display: flex;align-items: center;justify-content: center;padding: 0;border-radius: 50%;margin: 1px 0;}
						.active_sessions .as_list .session_info {display: block;margin-left: 51px;}
						.active_sessions .as_list .session_info h4 {margin-top: 0;margin-bottom: 4px;font-weight: 600;}
						.active_sessions .as_list .session_info p {margin-bottom: 8px;line-height: 1;margin-top: 0;font-size: 13px;color: #717171;}
					</style>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.1 14.8,9.5V11C15.4,11 16,11.6 16,12.3V15.8C16,16.4 15.4,17 14.7,17H9.2C8.6,17 8,16.4 8,15.7V12.2C8,11.6 8.6,11 9.2,11V9.5C9.2,8.1 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,9.5V11H13.5V9.5C13.5,8.7 12.8,8.2 12,8.2Z" /></svg> <?php echo __( 'Sessions' ); ?></h2>
					<div class="active_sessions">
						<?php foreach ($user_info['setting']->session as $key => $session) {
							$browser = 'Unknown';
				            $session['platform'] = ucfirst($session['platform']);
				            if ($session['platform'] == 'web' || $session['platform'] == 'windows') {
				                $session['platform'] = 'Unknown';
				            }
				            if ($session['platform'] == 'Phone') {
				                $browser = 'Mobile';
				            }
				            if ($session['platform'] == 'Windows') {
				                $browser = 'Desktop Application';
				            }
							?>
							<div class="as_list" id="session_<?php echo $session['id']?>">
								<div class="platform_icon">
									<?php 
									switch (strtolower($session['platform'])) {
										case 'windows':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#00adef" d="M3,12V6.75L9,5.43V11.91L3,12M20,3V11.75L10,11.9V5.21L20,3M3,13L9,13.09V19.9L3,18.75V13M20,13.25V22L10,20.09V13.1L20,13.25Z"></path></svg>';
										break;
										case 'linux':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#222" d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z"></path></svg>';
										break;
										case 'mac':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#222" d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z"></path></svg>';
										break;
										case 'iphone web':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#222" d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z"></path></svg>';
										break;
										case 'android web':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#A4C439" d="M15,5H14V4H15M10,5H9V4H10M15.53,2.16L16.84,0.85C17.03,0.66 17.03,0.34 16.84,0.14C16.64,-0.05 16.32,-0.05 16.13,0.14L14.65,1.62C13.85,1.23 12.95,1 12,1C11.04,1 10.14,1.23 9.34,1.63L7.85,0.14C7.66,-0.05 7.34,-0.05 7.15,0.14C6.95,0.34 6.95,0.66 7.15,0.85L8.46,2.16C6.97,3.26 6,5 6,7H18C18,5 17,3.25 15.53,2.16M20.5,8A1.5,1.5 0 0,0 19,9.5V16.5A1.5,1.5 0 0,0 20.5,18A1.5,1.5 0 0,0 22,16.5V9.5A1.5,1.5 0 0,0 20.5,8M3.5,8A1.5,1.5 0 0,0 2,9.5V16.5A1.5,1.5 0 0,0 3.5,18A1.5,1.5 0 0,0 5,16.5V9.5A1.5,1.5 0 0,0 3.5,8M6,18A1,1 0 0,0 7,19H8V22.5A1.5,1.5 0 0,0 9.5,24A1.5,1.5 0 0,0 11,22.5V19H13V22.5A1.5,1.5 0 0,0 14.5,24A1.5,1.5 0 0,0 16,22.5V19H17A1,1 0 0,0 18,18V8H6V18Z"></path></svg>';
										break;
										case 'mobile':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#222" d="M17,19H7V5H17M17,1H7C5.89,1 5,1.89 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3C19,1.89 18.1,1 17,1Z"></path></svg>';
										break;
										case 'phone':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#222" d="M17,19H7V5H17M17,1H7C5.89,1 5,1.89 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3C19,1.89 18.1,1 17,1Z"></path></svg>';
										break;
										case 'unknown':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#666" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z"></path></svg>';
										break;
										default:
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#666" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z"></path></svg>';
										break;
									}
									echo $icon;
									?>
								</div>
								<div class="session_info">
									<h4><?php echo $session['platform'] ?></h4>
									<p><?php echo $browser; ?> - <?php echo Time_Elapsed_String($session['time']); ?></p>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?> 
				<?php if (!empty($user_info['setting']->block)) { ?>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C17.67,14 23,15.33 23,18V20H7V18C7,15.33 12.33,14 15,14M15,12A4,4 0 0,1 11,8A4,4 0 0,1 15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12M5,9.59L7.12,7.46L8.54,8.88L6.41,11L8.54,13.12L7.12,14.54L5,12.41L2.88,14.54L1.46,13.12L3.59,11L1.46,8.88L2.88,7.46L5,9.59Z" /></svg> <?php echo __( 'Blocked Users' ); ?></h2>
					<div class="users_list">
						<?php foreach ($user_info['setting']->block as $member) { ?>
							<div class="profile-style" id="member-<?php echo $member->id ?>">
								<a href="<?php echo $site_url.'/@'.$member->username;?>">
									<div class="avatar">
										<img src="<?php echo $member->avater->avater;?>" alt="<?php echo $member->full_name; ?> Profile Picture" />
									</div>
								</a>
								<span><a href="<?php echo $site_url.'/@'.$member->username;?>"><?php echo $member->full_name.$member->pro_icon; ?></a></span>
							</div>
						<?php }  ?>
					</div>
				<?php } ?>
			<?php } ?>
				<?php if (!empty($user_info['all_friends'])) {
				 ?>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z" /></svg> <?php echo __('Friends'); ?></h2>
					<div class="users_list">
						<?php foreach ($user_info['all_friends'] as $key => $friend) { ?>
							<div class="profile-style" data-user-id="<?php echo $friend->id;?>">
								<a href="<?php echo $site_url.'/@'.$friend->username;?>" >
									<div class="avatar">
										<img src="<?php echo GetMedia($friend->avater);?>" alt="Profile Picture" />
									</div>
								</a>
								<span><a href="<?php echo $site_url.'/@'.$friend->username;?>"><?php echo $friend->username; ?></a></span>
							</div>
						<?php }  ?>
					</div>
				<?php } ?>
				<?php if (!empty($user_info['liked_users'])) {
				 ?>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C12.3,14 7,15.3 7,18V20H23V18C23,15.3 17.7,14 15,14M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12M5,15L4.4,14.5C2.4,12.6 1,11.4 1,9.9C1,8.7 2,7.7 3.2,7.7C3.9,7.7 4.6,8 5,8.5C5.4,8 6.1,7.7 6.8,7.7C8,7.7 9,8.6 9,9.9C9,11.4 7.6,12.6 5.6,14.5L5,15Z"></path></svg> <?php echo __('People i liked'); ?></h2>
					<div class="users_list">
						<?php foreach ($user_info['liked_users'] as $key => $friend) { ?>
							<div class="profile-style" data-user-id="<?php echo $friend->id;?>">
								<a href="<?php echo $site_url.'/@'.$friend->username;?>" >
									<div class="avatar">
										<img src="<?php echo GetMedia($friend->avater);?>" alt="Profile Picture" />
									</div>
								</a>
								<span><a href="<?php echo $site_url.'/@'.$friend->username;?>"><?php echo $friend->username; ?></a></span>
							</div>
						<?php }  ?>
					</div>
				<?php } ?>
				<?php if (!empty($user_info['disliked_users'])) {
				 ?>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M19,15H23V3H19M15,3H6C5.17,3 4.46,3.5 4.16,4.22L1.14,11.27C1.05,11.5 1,11.74 1,12V14A2,2 0 0,0 3,16H9.31L8.36,20.57C8.34,20.67 8.33,20.77 8.33,20.88C8.33,21.3 8.5,21.67 8.77,21.94L9.83,23L16.41,16.41C16.78,16.05 17,15.55 17,15V5C17,3.89 16.1,3 15,3Z"></path></svg> <?php echo __('People i disliked'); ?></h2>
					<div class="users_list">
						<?php foreach ($user_info['disliked_users'] as $key => $friend) { ?>
							<div class="profile-style" data-user-id="<?php echo $friend->id;?>">
								<a href="<?php echo $site_url.'/@'.$friend->username;?>" >
									<div class="avatar">
										<img src="<?php echo GetMedia($friend->avater);?>" alt="Profile Picture" />
									</div>
								</a>
								<span><a href="<?php echo $site_url.'/@'.$friend->username;?>"><?php echo $friend->username; ?></a></span>
							</div>
						<?php }  ?>
					</div>
				<?php } ?>
				<?php if ($user_info['show_media'] && !empty($user_info['mediafiles'])) {
				 ?>
					<h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></path></svg> <?php echo __('Media'); ?></h2>
					<div class="users_list">
						<?php foreach ($user_info['mediafiles'] as $key => $media) { ?>
							<div class="profile-style">
								<?php if ($media['is_video'] == 1) { ?>
									<video controls src="<?php echo $media['video_file'];?>" width="100%"></video>
								<?php }else{ ?>
									<a href="<?php echo $media['avater'];?>" >
										<div class="avatar">
											<img src="<?php echo $media['avater'];?>"/>
										</div>
									</a>
								<?php } ?>
								
							</div>
						<?php }  ?>
					</div>
				<?php } ?>
			
		
		<footer>
			<p><?php echo __( 'Copyright' );?> © <?php echo date( "Y" ) . " " . ucfirst( $config->site_name );?>. <?php echo __( 'All rights reserved' );?>.</p>
		</footer>
		</div>
	</body>
</html>