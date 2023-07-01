<fieldset>
    <legend>Speach</legend>
    <table>
        <tr>
            <td>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="include_start_formation" id="include_start_formation" value="1" {{ old('include_start_formation', $user->include_start_formation) ? 'checked' : '' }}>
                    <label class="form-check-label" for="include_start_formation">Include Start Formation</label>
                </div>
            </td>
            <td>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="include_end_formation" id="include_end_formation" value="1" {{ old('include_end_formation', $user->include_end_formation) ? 'checked' : '' }}>
                    <label class="form-check-label" for="include_end_formation">Include End Formation</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <label for="repeats">Number of Repeats:</label>
                    <input type="number" class="form-control" name="repeats" id="repeats" min="0" max="{{ $maxRepeats }}" value="{{ old('repeats', $user->repeats) }}">
                </div>
            </td>
            <td>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="include_formations_in_repeats" id="include_formations_in_repeats" value="1" {{ old('include_formations_in_repeats', $user->include_formations_in_repeats) ? 'checked' : '' }}>
                    <label class="form-check-label" for="include_formations_in_repeats">Include Formations in Repeats</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <button class="btn btn-primary" onClick="createMp3Text()">Create mp3 text</button>
                <br>&nbsp;
            </td>
            <td>
                <button class="btn btn-primary" onClick="createMp3File()">Create mp3 file</button>
                <br>&nbsp;
            </td>

        </tr>
        <tr id="mp3Row" style="display: none;">
            <td colspan="2" style="background-color: coral;">

                <audio id="player"  controls style="pointer-events:none" >
                    <source id="audioSource"  type="audio/mpeg">
                </audio>
            </td>
        </tr>
        <tr id="pathRow">
            <td colspan="2" style="background-color: #000;">
                <!--<div  style="overflow-x: scroll;">-->
                    <p id="path" style="background-color: #333;">
                <!--</div>-->
            </td>
        </tr>

    </table>
</fieldset>
