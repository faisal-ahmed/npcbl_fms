<section id="recent-works">
    <div class="container">
        <div class="row">
            <div class="wow fadeInDown">
                <h2 class="center">Latest Top Contents</h2>
                <form action="" method="post" id="latestContentForm" role="form">
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <label for="content-provider">Content Providers</label>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <select name="contentProvider" id="contentProvider" class="form-control menuselection" style="height: 34px;">
                            <?php foreach ($contentProvider['sources'] as $key => $value) { ?>
                                <option <?php if (isset($_REQUEST['contentProvider']) && $_REQUEST['contentProvider'] == $value->id) echo 'selected="selected" '; ?> value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <ul class="portfolio-filter text-center" style="margin-bottom: 15px;">
                        <input type="hidden" id="contentFilter" name="contentFilter" value="<?php echo $filter ?>" />
                        <li><a class="btn btn-default <?php if ($filter == 'sortT') echo "active" ?>" href="#" onclick="updateFilter('sortT')">Top Content</a></li>
                        <li><a class="btn btn-default <?php if ($filter == 'sortL') echo "active" ?>" href="#" onclick="updateFilter('sortL')">Latest Content</a></li>
                        <li><a class="btn btn-default <?php if ($filter == 'sortP') echo "active" ?>" href="#" onclick="updateFilter('sortP')">Popular Content</a></li>
                    </ul>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg" required="required">Apply Filter</button>
                        <?php if ($loggedIn != 'true') { ?><a href="<?php echo base_url() ?>index.php/Auth/register" style="vertical-align: -webkit-baseline-middle;">Want to have more controls? Then register here.</a><?php } ?>
                    </div>
                </form>
            </div>

            <?php if ($status == 'success') { ?>
            <table id="homeTable">
                <thead>
                    <tr>
                        <th class="sorting_asc" rowspan="1" colspan="1" style="width: 20%;">Content Picture</th>
                        <th class="sorting_asc" rowspan="1" colspan="1" style="width: 80%;">Content Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0; foreach ($content['articles'] as $key => $value) { ?>
                        <tr class="fadeInDown myTableTr">
                            <td class="post_reply text-center">
                                <a href="#"><img src="<?php echo $value->urlToImage ?>" class="img-circle" alt="" /></a>
                            </td>
                            <td>
                                <h3><a href="<?php echo $value->url ?>" target="_blank"><?php echo $value->title ?></a></h3>
                                <p class="lead"><?php echo $value->description ?></p>
                                <p><strong>On: </strong><?php echo str_replace("T", " ", $value->publishedAt) ?></p>
                                <p><strong>Direct Url: </strong><a href="<?php echo $value->url ?>" target="_blank"><?php echo $value->url ?></a></p>
                                <?php if ($loggedIn == 'true') { ?>
                                <?php $provider = isset($_REQUEST['contentProvider']) ? $_REQUEST['contentProvider'] : "abc-news-au"; ?>
                                <p>
                                    <a href="<?php echo base_url() ?>index.php/Bookmark?url=<?php echo $value->url ?>&title=<?php echo $value->title ?>&im=<?php echo $value->urlToImage ?>&s=<?php echo $provider?>" target="_blank">Bookmark</a> |
                                    <a href="<?php echo base_url() ?>index.php/Discussion?url=<?php echo $value->url ?>&title=<?php echo $value->title ?>&im=<?php echo $value->urlToImage ?>&s=<?php echo $provider?>" target="_blank">Join in Discussion</a>
                                </p>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="media reply_section wow fadeInDown">
                    <div class="status alert alert-danger"><?php echo $content; ?>.</div>
                </div>
            <?php } ?>

        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#middle-->

<script type="text/javascript">
    function updateFilter(value){
        jQuery("#contentFilter").val(value);
    }
</script>