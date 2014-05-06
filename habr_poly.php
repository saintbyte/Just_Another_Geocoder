<?php
public static function into_poly($sx, $sy, &$coords, $x='x', $y='y')
{
    Profiler::start('Detect collision', __FUNCTION__);
    $pj=0;
    $pk=0;
    $wrkx=0;
    $yu = 0;
    $yl = 0;
    $n = count($coords);
    for ($pj=0; $pj<$n; $pj++)
    {
        $yu = $coords[$pj][$y]>$coords[($pj+1)%$n][$y]?$coords[$pj][$y]:$coords[($pj+1)%$n][$y];
        $yl = $coords[$pj][$y]<$coords[($pj+1)%$n][$y]?$coords[$pj][$y]:$coords[($pj+1)%$n][$y];
        if ($coords[($pj+1)%$n][$y] - $coords[$pj][$y])
            $wrkx = $coords[$pj][$x] + ($coords[($pj+1)%$n][$x] - $coords[$pj][$x])*($sy - $coords[$pj][$y])/($coords[($pj+1)%$n][$y] - $coords[$pj][$y]);
        else
            $wrkx = $coords[$pj][$x];
        if ($yu >= $sy)
            if ($yl < $sy)
            {
                if ($sx > $wrkx)
                    $pk++;
                if (abs($sx - $wrkx) < 0.00001) return 1;
            }
        if ((abs($sy - $yl) < 0.00001) && (abs($yu - $yl) < 0.00001) && (abs(abs($wrkx - $coords[$pj][$x]) + abs($wrkx - $coords[($pj+1)%$n][$x]) - abs($coords[$pj][$x] - $coords[($pj+1)%$n][$x])) < 0.0001))
            return 1;
    }
    if ($pk%2)
        return 1;
    else
        return 0;
}
