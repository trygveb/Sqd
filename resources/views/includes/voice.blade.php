             <fieldset style="display:none;" id="fieldset_voice">
                    <legend>Voice</legend>
<table>
    <tr>
        <td>
                    <div class="form-group">
                        <label for="language">Language:</label>
                        <select class="form-control" name="language" id="language" onchange="GetVoiceList()">
                            @foreach($languageList as $language)
                            <option value="{{ $language }}" {{ old('language', $user->language) === $language ? 'selected' : '' }}>
                                {{ $language }}
                            </option>
                            @endforeach
                        </select>
                    </div>
        </td>
        <td>
                    <div class="form-group">
                        <label for="voice_type">Voice Type:</label>
                        <select class="form-control" name="voice_type" id="voice_type" onchange="GetVoiceList()">
                            @foreach($voiceTypeList as $voiceType)
                            <option value="{{ $voiceType }}" {{ old('voice_type', $user->voice_type) === $voiceType ? 'selected' : '' }}>
                                {{ $voiceType }}
                            </option>
                            @endforeach
                        </select>
                    </div>
        </td>
    </tr>
    <tr>
        <td>
                    <div class="form-group">
                        <label>Voice Gender:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="voice_gender" id="voice_gender_male" value="1" onchange="GetVoiceList()"
                                   {{ old('voice_gender', $user->voice_gender) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="voice_gender_male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="voice_gender" id="voice_gender_female" value="2" onchange="GetVoiceList()"
                                   {{ old('voice_gender', $user->voice_gender) == 2 ? 'checked' : '' }}>
                            <label class="form-check-label" for="voice_gender_female">Female</label>
                        </div>
                    </div>
        </td>
        <td>
                    
                    <div class="form-group">
                        <label for="voice_name">Voice Name:</label>
                        <select class="form-control" name="voice_name" id="voice_name">
                            <option>{{$user->voice_name }}</option>
                        </select>
                    </div>
        </td>
    </tr>
    <tr>
        <td>
                    
                    <div class="form-group">
                        <label for="voice_pitch">Voice Pitch:</label>
                        <input type="number" class="form-control" name="voice_pitch" id="voice_pitch" min="-20.0" max="20.0" step="0.1" value="{{ old('voice_pitch', $user->voice_pitch) }}">
                    </div>
        </td>
        <td>

                    <div class="form-group">
                        <label for="speaking_rate">Speaking Rate:</label>
                        <input type="number" class="form-control" name="speaking_rate" id="speaking_rate" min="0.25" max="4.0" step="0.01" value="{{ old('speaking_rate', $user->speaking_rate) }}">
                    </div>
        </td>
    </tr>
    <tr>
        <td>

                    <div class="form-group">
                        <label for="volume_gain">Volume Gain:</label>
                        <input type="number" class="form-control" name="volume_gain" min="-96.0" max="10.0" step="0.1" value="{{ old('volume_gain', $user->volume_gain_db) }}">
                    </div>

        </td>
    </tr>
</table>
                </fieldset>
