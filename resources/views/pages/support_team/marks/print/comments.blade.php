<div>
    <table class="table table-bordered " >
        <tbody class="text-start">
            <tr class="text-start">
                <td colspan=""  class="text-start"><strong>CLASS TEACHER'S COMMENT:</strong></td>
                <td colspan="2" class="text-center w-75">  {{ $exr->t_comment ?: str_repeat('__', 40) }}</td>
            </tr>
            <tr>
                <td colspan=""></td>
                <th colspan="" class="text-center w-25">SIGN & DATE</th>
                <td colspan="" class="border-bottom"></td>
            </tr>
            <tr>
                <td colspan="" class="text-start"><strong>PRINCIPAL'S COMMENT:</strong></td>
                <td colspan="2" class="text-center">  {{ $exr->p_comment ?: str_repeat('__', 40) }}</td>
            </tr>
            <tr>
                <td colspan=""></td>
                <th class="text-center">SIGN & DATE</th>
                <td colspan="" class="border-bottom"></td>
            </tr>
            <tr>
                <td colspan="" class="text-start"><strong>NEXT TERM BEGINS:</strong></td>
                <td colspan="2" class="text-center">{{ date('l\, jS F\, Y', strtotime($s['term_begins'])) }}</td>
            </tr>
            <tr>
                <td colspan="" class="text-start"><strong>NEXT TERM FEES:</strong></td>
                <td colspan="2" class="text-center"><del style="text-decoration-style: double">N</del></td>
            </tr>

        </tbody>
    </table>
</div>
