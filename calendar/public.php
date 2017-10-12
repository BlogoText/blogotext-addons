<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

function a_calendar()
{
    // Get the post ID
    $date = date('Ym');
    $postId = (string)filter_input(INPUT_GET, 'd');
    if (preg_match('#^\d{4}(/\d{2}){5}#', $postId)) {
        $postId = (int)substr(str_replace('/', '', $postId), 0, 14);
        $date = substr(get_entry('articles', 'bt_date', $postId), 0, 8);
        $date = ($date <= date('Ymd')) ? $date : date('Ym');
    } elseif (preg_match('#^\d{4}/\d{2}(/\d{2})?#', $postId)) {
        $date = str_replace('/', '', $postId);
        $date = (preg_match('#^\d{6}\d{2}#', $date)) ? substr($date, 0, 8) : substr($date, 0, 6);
    } elseif (preg_match('#^\d{14}#', $postId)) {
        $date = substr($postId, 0, 8);
    }
    // quick fix for #12 (http 500 when url modified)
    if (!is_int($date)) {
        $date = date('Ym');
    }
    $year = substr($date, 0, 4);
    $thisMonth = substr($date, 4, 2);
    $thisDay = (strlen(substr($date, 6, 2)) == 2) ? substr($date, 6, 2) : '';

    $mode = (string)filter_input(INPUT_GET, 'mode');
    $qstring = ($mode != '') ? 'mode='.htmlspecialchars($mode).'&amp;' : '';

    $firstDay = mktime(0, 0, 0, $thisMonth, 1, $year);
    $daysInThisMonth = date('t', $firstDay);
    $dayOffset = date('w', $firstDay - 1);

    // We check if there is one or more posts/links/comments in the current month
    $datesList = array();
    switch ($mode) {
        case 'comments':
            $where = 'commentaires';
            break;
        case 'links':
            $where = 'links';
            break;
        case 'blog':
        default:
            $where = 'articles';
            break;
    }

    // We look for previous and next post dates
    list($previousPost, $nextPost) = a_calendar_prev_next_posts_($year, $thisMonth, $where);
    $previousMonth = '?'.$qstring.'d='.substr($previousPost, 0, 4).'/'.substr($previousPost, 4, 2);
    $nextMonth = '?'.$qstring.'d='.substr($nextPost, 0, 4).'/'.substr($nextPost, 4, 2);

    // List of days containing at least one post for this month
    $datesList = a_calendar_table_list_date_($year.$thisMonth, $where);

    // Calendar header
    $html = '<table id="calendar">'."\n";
    $html .= '<caption>';
    if ($previousPost !== null) {
        $html .= '<a href="'.$previousMonth.'">&#171;</a>&nbsp;';
    }
    $html .= '<a href="?'.$qstring.'d='.$year.'/'.$thisMonth.'">'.month_en_lettres($thisMonth).' '.$year.'</a>';
    if ($nextPost !== null) {
        $html .= '&nbsp;<a href="'.$nextMonth.'">&#187;</a>';
    }
    $html .= '</caption>'."\n".'<tr>'."\n";

    // Calendar days
    if ($dayOffset > 0) {
        for ($i = 0; $i < $dayOffset; $i++) {
            $html .=  '<td></td>';
        }
    }
    for ($day = 1; $day <= $daysInThisMonth; $day++) {
        $class = $day == ($thisDay) ? ' class="active"' : '';
        $link = $day;
        if (in_array($day, $datesList)) {
            $link = '<a href="?'.$qstring.'d='.$year.'/'.$thisMonth.'/'.str_pad($day, 2, '0', STR_PAD_LEFT).'">'.$day.'</a>';
        }
        $html .= '<td'.$class.'>'.$link.'</td>';
        $dayOffset++;
        if ($dayOffset == 7) {
            $dayOffset = 0;
            $html .=  '</tr>';
            if ($day < $daysInThisMonth) {
                $html .= '<tr>';
            }
        }
    }
    if ($dayOffset > 0) {
        for ($i = $dayOffset; $i < 7; $i++) {
            $html .= '<td> </td>';
        }
        $html .= '</tr>'."\n";
    }
    $html .= '</table>'."\n";

    return $html;
}

// Returns a list of days containing at least one post for a given month
function a_calendar_table_list_date_($date, $table)
{
    $return = array();
    $column = ($table == 'articles') ? 'bt_date' : 'bt_id';
    $query = '
        SELECT DISTINCT SUBSTR('.$column.', 7, 2) AS date
          FROM '.$table.'
         WHERE bt_statut = 1
               AND '.$column.' LIKE "'.$date.'%"'.'
               AND '.$column.' <= '.date('YmdHis');
    try {
        $req = $GLOBALS['db_handle']->query($query);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $return[] = $row['date'];
        }
        return $return;
    } catch (Exception $e) {
        return ((bool)DISPLAY_PHP_ERRORS) ? 'Error addon_calendar:a_calendar_table_list_date_(): '.$e->getMessage() : '';
    }
}

// Returns dates of the previous and next visible posts
function a_calendar_prev_next_posts_($year, $month, $table)
{
    $column = ($table == 'articles') ? 'bt_date' : 'bt_id';
    $date = new DateTime();
    $date->setDate($year, $month, 1)->setTime(0, 0, 0);
    $dateMin = $date->format('YmdHis');
    $date->modify('+1 month');
    $dateMax = $date->format('YmdHis');

    $query = '
        SELECT
            (SELECT SUBSTR('.$column.', 0, 7)
               FROM '.$table.'
              WHERE bt_statut = 1
                    AND '.$column.' < '.$dateMin.'
              ORDER BY '.$column.' DESC
              LIMIT 1),
            (SELECT SUBSTR('.$column.', 0, 7)
               FROM '.$table.'
              WHERE bt_statut = 1
                    AND '.$column.' > '.$dateMax.'
                    AND '.$column.' <= '.date('YmdHis').'
              ORDER BY '.$column.' ASC
              LIMIT 1)';

    try {
        $req = $GLOBALS['db_handle']->query($query);
        return array_values($req->fetch(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        return ((bool)DISPLAY_PHP_ERRORS) ? 'Error addon_calendar:a_calendar_prev_next_posts_(): '.$e->getMessage() : '';
    }
}
