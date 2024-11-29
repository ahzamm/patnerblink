<style>
.site-footer
{
  background-color:transparent;
  padding: 5px 0 5px;
  font-size:15px;
  line-height:24px;
  color:#737373;
  /* position:fixed; */
  /* bottom:0px; */
}
.site-footer hr
{
  border-top-color:#bbb;
  opacity:0.5
}
.site-footer hr.small
{
  margin:20px 0
}
.site-footer h6
{
  color:#fff;
  font-size:16px;
  text-transform:uppercase;
  margin-top:5px;
  letter-spacing:2px
}
.site-footer a
{
  color:#737373;
}
.site-footer a:hover
{
  color:#3366cc;
  text-decoration:none;
}
.footer-links
{
  padding-left:0;
  list-style:none
}
.footer-links li
{
  display:block
}
.footer-links a
{
  color:#737373
}
.footer-links a:active,.footer-links a:focus,.footer-links a:hover
{
  color:#3366cc;
  text-decoration:none;
}
.footer-links.inline li
{
  display:inline-block
}
.site-footer .social-icons
{
  text-align:right
}
.site-footer .social-icons a
{
  width:40px;
  height:40px;
  line-height:40px;
  margin-left:6px;
  margin-right:0;
  border-radius:100%;
  background-color:#33353d
}
.copyright-text
{
  margin:0;
  padding: 6px 0 6px;
}

/* footer social icons */
ul.social-network {
	list-style: none;
	display: inline;
	/* margin-left:300px !important; */
	padding: 0;
  position: absolute;
  right: 20px;
}
ul.social-network li {
	display: inline;
	margin: 0 5px;
}


/* footer social icons */
.social-network a.icoRss:hover {
	background-color: #F56505;
}
.social-network a.icoFacebook:hover {
	background-color:#3B5998;
}
.social-network a.icoTwitter:hover {
	background-color:#33ccff;
}
.social-network a.icoGoogle:hover {
	background-color:#BD3518;
}
.social-network a.icoVimeo:hover {
	background-color:#0590B8;
}
.social-network a.icoLinkedin:hover {
	background-color:#007bb7;
}
.social-network a.icoRss:hover i, .social-network a.icoFacebook:hover i, .social-network a.icoTwitter:hover i,
.social-network a.icoGoogle:hover i, .social-network a.icoVimeo:hover i, .social-network a.icoLinkedin:hover i {
	color:#fff;
}
a.socialIcon:hover, .socialHoverClass {
	color:#44BCDD;
}

.social-circle li a {
	display:inline-block;
	position:relative;
	margin:0 auto 0 auto;
	-moz-border-radius:50%;
	-webkit-border-radius:50%;
	border-radius:50%;
	text-align:center;
	width: 40px;
	height: 40px;
	font-size:20px;
}
.social-circle li i {
	margin:0;
	line-height:40px;
	text-align: center;
}

.social-circle li a:hover i, .triggeredHover {
	-moz-transform: rotate(360deg);
	-webkit-transform: rotate(360deg);
	-ms--transform: rotate(360deg);
	transform: rotate(360deg);
	-webkit-transition: all 0.9s;
	-moz-transition: all 0.9s;
	-o-transition: all 0.9s;
	-ms-transition: all 0.9s;
	transition: all 0.9s;
}
.social-circle i {
	color: #fff;
	-webkit-transition: all 0.9s;
	-moz-transition: all 0.9s;
	-o-transition: all 0.9s;
	-ms-transition: all 0.9s;
	transition: all 0.9s;
}

.bg {
 background-color: #b9b5b5;   
}
.social-circle{
  margin-left: 20px;
}
.setting{
  margin-top:12px;
}
.footer {
  /* position: fixed;
  left: 0;
  bottom: 0; */
  width: 100%;
  background-color: #ffffff;
}
@media (max-width:991px)
{
  .site-footer [class^=col-]
  {
    /* margin-bottom:30px */
    margin-bottom:0;
    
  }
  ul.social-network{
    text-align: center;
    position: relative;
  }
}
@media (max-width:767px)
{
  .site-footer
  {
    padding-bottom:0
  }
  .site-footer .copyright-text,.site-footer .social-icons
  {
    text-align:center
  }
}
    </style>
  <!-- Site footer -->
  <footer class="site-footer footer">
      <div class="row" style="margin: 0">
      <div class="col-md-6 col-sm-6 col-xs-12">
          <p class="copyright-text">Copyright &copy; <b>2012</b> Designed and Developed by 
       <a href="http://squadcloud.co" target="_blank"><span style="color: blue">SquadCloud</span></a>.
          </p>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-center">
          <ul class="social-network social-circle">
            <!-- <li><a href="#" class="icoRss bg" title="Rss"><i class="fa fa-rss"></i></a></li> -->
            <li><a href="#" class="icoFacebook bg" title="Facebook"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#" class="icoTwitter bg" title="Twitter"><i class="fab fa-twitter"></i></a></li>
            <!-- <li><a href="#" class="icoGoogle bg" title="Google +"><i class="fa fa-google-plus"></i></a></li> -->
            <li><a href="#" class="icoLinkedin bg" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
          </ul>
        </div>
        
      </div>

</footer>
